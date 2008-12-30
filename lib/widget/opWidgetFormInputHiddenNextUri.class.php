<?php

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

    // FIXME
    $params = http_build_query($request->getGetParameters());
    $value = $routing->getCurrentInternalUri().'?'.str_replace('openid_', 'openid.', $params);

    $this->setAttribute('value', $value);
  }
}
