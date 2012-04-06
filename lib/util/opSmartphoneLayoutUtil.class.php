<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opNotificationCenter
 *
 * @package    OpenPNE
 * @subpackage util
 * @author     Shouta Kashiwagi <kashiwagi@tejimaya.com>
 */
class opSmartphoneLayoutUtil
{
  protected static $parameters;

  static public function setLayoutParameters($parameters)
  {
    self::$parameters = $parameters;
    sfContext::getInstance()->getEventDispatcher()->connect('template.filter_parameters', array(__CLASS__, 'filterTemplateParameters'));
  }

  static public function filterTemplateParameters(sfEvent $event, $parameters)
  {
    if (isset($parameters['sf_type']) && 'layout' === $parameters['sf_type'])
    {
      $parameters['op_layout'] = self::$parameters;
    }

    return $parameters;
  }
}
