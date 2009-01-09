<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * OpenPNEFormAutoGenerate is the base class for forms that generate its widgets automatically
 *
 * This class is deprecated because it does call the static method of opFormItemGenerator only.
 * If you want to generate form items, please use the opFormItemGenerator class.
 *
 * @package    OpenPNE
 * @subpackage form
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
abstract class OpenPNEFormAutoGenerate extends sfForm
{
  public function __construct($defaults = array(), $options = array(), $CSRFSecret = null)
  {
    parent::__construct($defaults, $options, $CSRFSecret);

    $message = 'The OpenPNEFormAutoGenerate is deprecated. '
             . 'Please use static methods of the opFormItemGenerator class. '
             . 'The OpenPNEFormAutoGenerate will be deleted by OpenPNE3.0beta3.';
    sfContext::getInstance()->getConfiguration()->getEventDispatcher()->notify(
      new sfEvent(null, 'application.log', array($message, 'priority' => sfLogger::ERR))
    );
  }

  protected function generateWidget($field, $choices = array())
  {
    return opFormItemGenerator::generateWidget($field, $choices);
  }

  protected function generateValidator($field, $choices = array())
  {
    return opFormItemGenerator::generateValidator($field, $choices);
  }
}
