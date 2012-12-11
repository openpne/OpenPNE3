<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * customizingCss action.
 *
 * @package    OpenPNE
 * @subpackage default
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class customizingCssAction extends sfAction
{
  public function execute($request)
  {
    $css = Doctrine::getTable('SnsConfig')->get('customizing_css');
    $this->getResponse()->setContent($css);
    $this->getResponse()->setContentType('text/css');

    // cache
    $filesystem = new sfFilesystem();
    $dir = sfConfig::get('sf_web_dir').DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR.'css';
    @$filesystem->mkdirs($dir);
    $filesystem->chmod($dir, 0777);
    $cssPath = $dir.DIRECTORY_SEPARATOR.'customizing.css';
    file_put_contents($cssPath, $css);
    $filesystem->chmod($cssPath, 0666);

    return sfView::NONE;
  }
}
