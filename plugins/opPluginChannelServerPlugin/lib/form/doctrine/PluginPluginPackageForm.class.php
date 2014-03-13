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
 * PluginPluginPackage form.
 *
 * @package    opPluginChannelServerPlugin
 * @subpackage form
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
abstract class PluginPluginPackageForm extends BasePluginPackageForm
{
  public function setup()
  {
    parent::setup();

    $redmineChoices = array(
      '1' => 'Use',
      '0' => 'Do not use',
    );

    $this
      ->setWidget('repository', new sfWidgetFormInputText(array('label' => 'Repository URL')))
      ->setWidget('bts', new sfWidgetFormInputText(array('label' => 'BTS URL')))
      ->setWidget('summary', new sfWidgetFormInputText())
      ->setWidget('license', new sfWidgetFormInputText())
      ->setWidget('is_relating_redmine', new sfWidgetFormChoice(array('choices' => $redmineChoices)))
      ->addEditableImageFormWidget('file_id', array('label' => 'Image'))

      ->setValidator('name', new sfValidatorCallback(array('callback' => array($this, 'validatePluginName'), 'required' => true)))
      ->setValidator('repository', new sfValidatorUrl(array('required' => false)))
      ->setValidator('bts', new sfValidatorUrl(array('required' => false)))
      ->setValidator('file_id', new opValidatorImageFile(array('required' => false)))
      ->setValidator('is_relating_redmine', new sfValidatorChoice(array('choices' => array_keys($redmineChoices))))
      ->setValidator('id', new opValidatorString(array('required' => false)))
    ;

    $this->widgetSchema
      ->setLabel('name', 'Plugin Name')
      ->setLabel('is_relating_redmine', 'Use related redmine')

      ->setHelp('is_relating_redmine', 'If you select "Use", you can use a project of related redmine, and your inputted "Bts" value will be overwritten')
      ->setHelp('name', 'Plugin name must start with "op" and end with "Plugin". If you want to use related redmine, a length of the plugin name must be less than 24 characters.')
      ->setHelp('license', 'License should be "MIT", "BSD", "LGPL", "PHP", "Apache" (case-insensitive). If you select other license, plugin installer will output notice.')
    ;

    if (sfConfig::get('op_is_use_captcha', false) && $this->isNew())
    {
      $this->embedForm('captcha', new opCaptchaForm());
    }

    $this->useFields(array(
      'name', 'summary', 'description', 'license',
      'category_id', 'repository', 'bts',
      'is_relating_redmine', 'file_id',
    ), true);

    if (!$this->isNew())
    {
      unset($this['name']);
    }

    $this->mergePostValidator(new sfValidatorCallback(array('callback' => array($this, 'validateProjectCreation'))));
  }

  public function bind(array $taintedValues = null, array $taintedFiles = null)
  {
    unset($taintedValues['id']);
    if (!$this->isNew())
    {
      $taintedValues['id'] = $this->getObject()->id;
    }

    parent::bind($taintedValues, $taintedFiles);
  }

  public function validateProjectCreation($validator, $value, $arguments)
  {
    if ($this->isNew())
    {
      $name = $value['name'];
    }
    else
    {
      $name = $this->getObject()->getName();
      if ($this->getObject()->isRelatingRedmine())
      {
        return $value;
      }
    }

    $baseUrl = opPluginChannelServerToolkit::getConfig('related_redmine_base_url', 'http://redmine.openpne.jp/');
    if (!$value['is_relating_redmine'] || 0 === strpos($value['bts'], $baseUrl))
    {
      return $value;
    }

    if (24 < strlen($name))
    {
      throw new sfValidatorError($this->validatorSchema['is_relating_redmine'], 
        'The relating redmine project cannot be created for this plugin because of the name is longer than 24 characters.'
        .'If you want to create and relate the redmine project, please create project automatically and fill the "bts" field.'
      );
    }

    return $value;
  }

  public function generatePluginIdentifier($name)
  {
    $identifier = 'plg-'.strtolower(substr($name, 2, -6));

    return $identifier;
  }

  public function validatePluginName($validator, $value, $arguments)
  {
    $_validator = new opValidatorString(array('max_length' => 64));
    $value = $_validator->clean($value);

    if (!preg_match('/^op.+Plugin$/', $value))
    {
      throw new sfValidatorError($validator, 'invalid');
    }

    return $value;
  }

  public function updateObject($values = null)
  {
    $member = sfContext::getInstance()->getUser()->getMember();
    $baseUrl = opPluginChannelServerToolkit::getConfig('related_redmine_base_url', 'http://redmine.openpne.jp/');

    if (is_null($values))
    {
      $values = $this->getValues();
    }

    $image = null;
    if (array_key_exists('file_id', $values))
    {
      $image = $values['file_id'];
      unset($values['file_id']);
    }

    $obj = parent::updateObject($values);
    $obj->save();

    if ($this->isNew())
    {
      $obj->PluginMember[0]->Member = $member;
      $obj->PluginMember[0]->position = 'lead';
      $obj->PluginMember[0]->is_active = true;
    }

    if ($image instanceof sfValidatedFile)
    {
      $oldImage = clone $obj->Image;

      $obj->Image = new File();
      $obj->Image->setFromValidatedFile($image);
      $obj->Image->name = 'plugin_'.$obj->getId().'_'.$obj->Image->name;
      if ($oldImage)
      {
        $oldImage->delete();
      }
    }
    elseif ($this->getValue('file_id_delete'))
    {
      if ($obj->Image)
      {
        $obj->Image->delete();
        $obj->Image = null;
      }
    }

    sfContext::getInstance()->getConfiguration()->loadHelpers('Url');
    $pluginUrl = url_for('package_home', $obj, true);

    $url = $this->injectAPIKeyToRedminUrl($baseUrl, $member->getConfig('redmine_api_token'));
    $user = new opRedmineUserResource();
    $user->find($member->getConfig('redmine_username'));

    $url = $this->injectAPIKeyToRedminUrl($baseUrl, $member->getConfig('redmine_api_token'));
    $project = new opRedmineProjectResource();
    $project->site = $url;
    $project->find($this->generatePluginIdentifier($obj->name));
    if ($project->error)
    {
      $parentId = opPluginChannelServerToolkit::getConfig('parent_project_id');

      $project = new opRedmineProjectResource(array(
        'name'        => $obj->name,
        'identifier'  => $this->generatePluginIdentifier($obj->name),
        'homepage'    => $pluginUrl,
        'description' => $obj->description,
        'parent_id'   => $parentId,
      ));
      $project->site = $url;
    }
    else
    {
      $project->set('description', $obj->description);
      $project->set('homepage', $pluginUrl);
    }
    $result = $project->save();

    $projectMember = new opRedmineMemberResource(array(
      'user_ids' => array($user->id),
      'role_ids' => array(opPluginChannelServerToolkit::getConfig('user_role_id', 1)),
    ));
    $projectMember->site = $url.'/projects/'.$this->generatePluginIdentifier($obj->name).'/';
    $projectMember->save();

    if (!empty($values['is_relating_redmine']))
    {
      $obj->bts = $baseUrl.'projects/'.$this->generatePluginIdentifier($obj->name);
    }

    $obj->save();
  }

  protected function injectAPIKeyToRedminUrl($url, $key)
  {
    $result = parse_url($url, PHP_URL_SCHEME).'://'
      . $key.'@'
      . parse_url($url, PHP_URL_HOST)
      . (parse_url($url, PHP_URL_PORT) ? ':'.parse_url($url, PHP_URL_PORT) : '')
      . (parse_url($url, PHP_URL_PATH) ? parse_url($url, PHP_URL_PATH) : '/')
      . (parse_url($url, PHP_URL_QUERY) ? '?'.parse_url($url, PHP_URL_QUERY) : '')
      . (parse_url($url, PHP_URL_FRAGMENT) ? '#'.parse_url($url, PHP_URL_FRAGMENT) : '');

    return $result;
  }

  protected function processUploadedFile($field, $filename = null, $values = null)
  {
    return '';
  }

  public function addEditableImageFormWidget($name, $options = array())
  {
    $options = array_merge(array(
      'file_src'     => '',
      'is_image'     => true,
      'with_delete'  => true,
      'delete_label' => sfContext::getInstance()->getI18N()->__('Remove the current photo')
    ), $options);

    if (!$this->isNew() && $this->getObject()->$name)
    {
      sfContext::getInstance()->getConfiguration()->loadHelpers('Partial');
      $options['edit_mode'] = true;
      $options['template'] = get_partial('default/formEditImage', array('image' => $this->getObject()));
      $this->setValidator('file_id_delete', new sfValidatorBoolean(array('required' => false)));
    }
    else
    {
      $options['edit_mode'] = false;
    }

    $this->setWidget($name, new sfWidgetFormInputFileEditable($options, array('size' => 40)));

    return $this;
  }
}
