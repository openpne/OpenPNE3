<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opCaptchaForm
 *
 * @package    OpenPNE
 * @subpackage form
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opCaptchaForm extends BaseForm
{
  public function configure()
  {
    $this->setWidget('captcha', new opWidgetFormCaptcha());
    $this->setValidator('captcha', new sfValidatorPass());

    $formatter = new sfWidgetFormSchemaFormatterList($this->widgetSchema);
    $formatter->setRowFormat("<li>%field%%help%\n%hidden_fields%</li>\n");
    $formatter->setHelpFormat('<div class="help">%help%</div>');

    $this->widgetSchema->addFormFormatter('opCaptchaFormFormatter', $formatter);
    $this->widgetSchema->setFormFormatterName('opCaptchaFormFormatter');

    $this->validatorSchema->setPostValidator(new sfValidatorCallback(array(
      'callback' => array($this, 'validateCaptchaString'),
    )));

    $this->widgetSchema->setHelp('captcha', 'Please input the below keyword.');
  }

  public function validateCaptchaString($validator, $value, $arguments)
  {
    $answer = '';
    if (isset($_SESSION['captcha_keystring']))
    {
      $answer = $_SESSION['captcha_keystring'];
      unset($_SESSION['captcha_keystring']);
    }

    if ($value['captcha'] !== $answer)
    {
      $error = new sfValidatorError($validator, 'invalid');

      throw new sfValidatorErrorSchema($validator, array('captcha' => $error));
    }

    return $value;
  }
}
