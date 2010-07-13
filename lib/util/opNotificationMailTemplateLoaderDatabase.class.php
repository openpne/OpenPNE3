<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opNotificationMailTemplateLoaderDatabase
 *
 * @package    OpenPNE
 * @subpackage util
 * @author     Shogo Kawahara <kawahara@bucyou.net>
 */
class opNotificationMailTemplateLoaderDatabase extends sfTemplateAbstractSwitchableLoader
{
  public function doLoad($template, $renderer = 'twig')
  {
    $string = Doctrine::getTable('NotificationMail')->fetchTemplate($template);
    if (!(string)$string)
    {
      return false;
    }

    return new sfTemplateStorageString((string)$string, $renderer);
  }
}
