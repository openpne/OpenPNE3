<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opWidgetFormInputHiddenNextUri represents a hidden HTML input tag for next_uri
 *
 * @package    OpenPNE
 * @subpackage widget
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opWidgetFormInputHiddenNextUri extends sfWidgetFormInputHidden
{
  protected function configure($options = array(), $attributes = array())
  {
    parent::configure($options, $attributes);

    $routing = sfContext::getInstance()->getRouting();
    $request = sfContext::getInstance()->getRequest();

    $params = http_build_query($request->getGetParameters());
    $value = $routing->getCurrentInternalUri();
    if ($params)
    {
      $value .= '?'.str_replace('openid_', 'openid.', $params);
    }

    $this->setAttribute('value', $value);
  }

  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    $attributes = array_merge($attributes, $this->getAttributes());
    return parent::render($name, $value, $attributes, $errors);
  }
}
