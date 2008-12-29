<?php

/**
 * opValidatorNextUri validates a next_uri.
 *
 * @package    OpenPNE
 * @subpackage validator
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opValidatorNextUri extends sfValidatorString
{
  /**
   * @see sfValidatorString
   */
  protected function doClean($value)
  {
    if (!$value)
    {
      return '@homepage';
    }

    $routing = sfContext::getInstance()->getRouting();
    $routeInfo = $routing->findRoute($value);

    if (sfConfig::get('sf_login_module') === $routeInfo['parameters']['module']
      && sfConfig::get('sf_login_action') === $routeInfo['parameters']['action'])
    {
      return '@homepage';
    }

    return $value;
  }
}
