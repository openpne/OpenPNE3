<?php

/**
* Copyright 2010 Kousuke Ebihara
*
* Licensed under the Apache License, Version 2.0 (the "License");
* you may not use this file except in compliance with the License.
* You may obtain a copy of the License at
*
* http://www.apache.org/licenses/LICENSE-2.0
*
* Unless required by applicable law or agreed to in writing, software
* distributed under the License is distributed on an "AS IS" BASIS,
* WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
* See the License for the specific language governing permissions and
* limitations under the License.
*/

/**
 * Plugin release from
 *
 * @package    opPluginChannelServerPlugin
 * @subpackage form
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opPluginPackageReleaseForm extends BaseForm
{
  protected $package;

  public function configure()
  {
    $this
      ->setWidget('tgz_file', new sfWidgetFormInputFile())
      ->setValidator('tgz_file', new sfValidatorFile(array('required' => false)))

      ->setWidget('svn_url', new sfWidgetFormInputText())
      ->setValidator('svn_url', new sfValidatorUrl(array('required' => false)))

      ->setWidget('git_url', new sfWidgetFormInputText())
      ->setValidator('git_url', new sfValidatorString(array('required' => false)))

      ->setWidget('git_commit', new sfWidgetFormInputText())
      ->setValidator('git_commit', new sfValidatorString(array('required' => false)))
    ;

    $this->widgetSchema
      ->setNameFormat('plugin_release[%s]')

      ->setHelp('svn_url', 'Please specify tag url')
      ->setHelp('git_url', 'The url must be HTTP protocol format. e.g. http://github.com/openpne/opSamplePlugin.git')
      ->setHelp('git_commit', 'This can be many formatted string: "master" (branch name), "9af9b" (commit object name), "v1.0.0" (tag name)')
    ;

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
  }

  public function setPluginPackage($package)
  {
    $this->package = $package;
  }

  protected function getChannel()
  {
    $host = sfContext::getInstance()->getRequest()->getHost();
    $serverName = opPluginChannelServerToolkit::getConfig('server_name', str_replace(':80', '', $host));

    $browser = new opBrowser($serverName);
    $browser->get('/channel.xml');

    $channel = new PEAR_ChannelFile();
    $channel->fromXmlString($browser->getResponse()->getContent());
    $channel->setName($serverName);

    return $channel;
  }

  public function uploadPackage()
  {
    $tgz = $this->getValue('tgz_file');
    $svn = $this->getValue('svn_url');
    $gitUrl = $this->getValue('git_url');
    $gitCommit = $this->getValue('git_commit');
    $memberId = sfContext::getInstance()->getUser()->getMemberId();

    $pear = opPluginChannelServerToolkit::registerPearChannel($this->getChannel());

    if ($tgz)
    {
      require_once 'Archive/Tar.php';

      $info = $pear->infoFromTgzFile($tgz->getTempName());
      if ($info instanceof PEAR_Error)
      {
        throw new RuntimeException($info->getMessage());
      }

      $tar = new Archive_Tar($tgz->getTempName());
      $xml = '';
      foreach ($tar->listContent() as $file)
      {
        if ('package.xml' === $file['filename'])
        {
          $xml = $tar->extractInString($file['filename']);
        }
      }

      $file = new File();
      $file->setFromValidatedFile($tgz);
      $file->save();

      $release = Doctrine::getTable('PluginRelease')->createByPackageInfo($info, $file, $memberId, $xml);
      $this->package->PluginRelease[] = $release;
      $this->package->save();
    }
    elseif ($svn)
    {
      $dir = $this->importFromSvn($svn);
      $this->importSCMFile($pear, $memberId, $dir);
    }
    elseif ($gitUrl && $gitCommit)
    {
      $dir = $this->importFromGit($gitUrl, $gitCommit);
      $this->importSCMFile($pear, $memberId, $dir);
    }
  }

  protected function importSCMFile($pear, $memberId, $dir)
  {
    $filesystem = new sfFilesystem();

    $info = $pear->infoFromDescriptionFile($dir.'/package.xml');
    if ($info instanceof PEAR_Error)
    {
      throw new RuntimeException($info->message());
    }

    $filename = sprintf('%s-%s.tgz', $info['name'], $info['version']);
    opPluginChannelServerToolkit::generateTarByPluginDir($info, $filename, $dir, sfConfig::get('sf_cache_dir'));

    $file = $this->getImportedPluginFile($filename, sfConfig::get('sf_cache_dir').'/'.$filename);

    $release = Doctrine::getTable('PluginRelease')->createByPackageInfo($info, $file, $memberId, file_get_contents($dir.'/package.xml'));
    $this->package->PluginRelease[] = $release;
    $this->package->save();

    $filesystem = new sfFilesystem();
    sfToolkit::clearDirectory($dir);
    $filesystem->remove($dir);
    $filesystem->remove(sfConfig::get('sf_cache_dir').'/'.$filename);
  }

  protected function importFromSvn($url)
  {
    require_once 'VersionControl/SVN.php';

    $dir = sfConfig::get('sf_cache_dir').'/svn-'.md5($url);

    $result = VersionControl_SVN::factory('export', array(
      'svn_path' => 'svn', // FIXME: It should be configurable
    ))->run(array($url, $dir));

    return $dir;
  }

  protected function importFromGit($gitUrl, $gitCommit)
  {
    $filesystem = new sfFilesystem();

    require_once 'VersionControl/Git.php';

    $dir = sfConfig::get('sf_cache_dir').'/git-'.md5($gitUrl.$gitCommit);
    $filesystem->mkdirs($dir);

    $git = new VersionControl_Git(sfConfig::get('sf_cache_dir'));
    $git->createClone($gitUrl, false, $dir);
    $filesystem->chmod($dir, 0777);
    $git->checkout($gitCommit);

    return $dir;
  }

  protected function getImportedPluginFile($filename, $filepath)
  {
    $file = new File();
    $file->setType('application/x-gzip');
    $file->setOriginalFilename($filename);
    $file->setName(strtr($filename, '.', '_'));

    $bin = new FileBin();
    $bin->setBin(file_get_contents($filepath));
    $file->setFileBin($bin);
    $file->save();

    return $file;
  }
}
