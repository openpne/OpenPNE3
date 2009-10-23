<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opNotificationMailTemplateLoaderFilesystem
 *
 * @package    OpenPNE
 * @subpackage util
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */

class opNotificationMailTemplateLoaderFilesystem extends sfTemplateSwitchableLoaderFilesystemForSymfony1
{
  public function configure()
  {
    $dirs = $this->context->getConfiguration()->getGlobalTemplateDirs();
    foreach ($dirs as $k => $v)
    {
      $this->templateDirs[$k] = $v.'/mail/%name%.%extension%';
    }
  }

  public function doLoad($template, $renderer = 'php')
  {
    $template = str_replace(array('pc_', 'mobile_', 'admin_'), array('pc/_', 'mobile/_', 'admin/_'), $template);

    return parent::doLoad($template, $renderer);
  }
}
