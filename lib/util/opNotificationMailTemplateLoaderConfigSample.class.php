<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opNotificationMailTemplateLoaderConfigSample
 *
 * @package    OpenPNE
 * @subpackage util
 * @author     Shogo Kawahara <kawahara@bucyou.net>
 */
class opNotificationMailTemplateLoaderConfigSample extends sfTemplateAbstractSwitchableLoader
{
  public function doLoad($template, $renderer = 'twig')
  {
    if ($sample = Doctrine::getTable('NotificationMail')->fetchTemplateFromConfigSample($template))
    {
      if ($sample[1])
      {
        return new sfTemplateStorageString($sample[1], $renderer);
      }
    }

    return false;
  }
}
