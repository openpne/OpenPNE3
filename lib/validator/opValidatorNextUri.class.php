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
    $this->setOption('empty_value', '@homepage');
  }

  /**
   * @see sfValidatorString
   */
  protected function doClean($value)
  {
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
