<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opWidgetFormCaptcha represents a date widget.
 *
 * @package    OpenPNE
 * @subpackage widget
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opWidgetFormCaptcha extends sfWidgetFormInput
{
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    $input = parent::render($name, null, $attributes, $errors);

    $root = sfContext::getInstance()->getRequest()->getRelativeUrlRoot();
    $captchaImage = $this->renderTag('img', array('src' => $root.'/captcha.php', 'alt' => 'captcha'));

    $result = $this->renderContentTag('p', $captchaImage).$input;

    return $result;
  }
}
