<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opValidatorNextUri validates a next_uri.
 *
 * @package    OpenPNE
 * @subpackage validator
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opValidatorNextUri extends sfValidatorString
{
  protected function configure($options = array(), $messages = array())
  {
    parent::configure($options, $messages);
    $this->setOption('required', false);
    $this->setOption('trim', true);
    $this->setOption('empty_value', '@homepage');
    $this->addOption('logout_uri', 'member/logout');
  }

  /**
   * @see sfValidatorString
   */
  protected function doClean($value)
  {
    $clean = parent::doClean($value);

    $routing = sfContext::getInstance()->getRouting();

    $routeInfo = $routing->findRoute($clean);
    if ($routeInfo)
    {
      $module = $routeInfo['parameters']['module'];
      $action = $routeInfo['parameters']['action'];
    }
    else
    {
      return $this->getOption('empty_value');
    }

    if ($this->getOption('logout_uri'))
    {
      $logoutRouteInfo = $routing->findRoute($this->getOption('logout_uri'));
      $logoutModule = $logoutRouteInfo['parameters']['module'];
      $logoutAction = $logoutRouteInfo['parameters']['action'];
      if ($logoutModule === $module &&  $logoutAction === $action)
      {
        return $this->getOption('empty_value');
      }
    }

    if (sfConfig::get('sf_login_module') === $module && sfConfig::get('sf_login_action') === $action)
    {
      return $this->getOption('empty_value');
    }

    return $clean;
  }
}
