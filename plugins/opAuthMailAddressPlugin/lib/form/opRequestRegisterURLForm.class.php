<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 *
 *
 * @package    opAuthMailAddressPlugin
 * @subpackage form
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opRequestRegisterURLForm extends BaseForm
{
  protected
    $doNotSend = false,
    $member = null;

  public function configure()
  {
    $this->disableLocalCSRFProtection();

    $this->setWidget('mail_address', new sfWidgetFormInputText());
    $this->setValidator('mail_address', new sfValidatorPass());

    $callback = new sfValidatorCallback(array(
        'callback' => array($this, 'validate'),
    ));
    $callback->setMessage('invalid', 'invalid e-mail address');
    $this->validatorSchema->setPostValidator($callback);

    if (sfConfig::get('op_is_use_captcha', false))
    {
      $this->embedForm('captcha', new opCaptchaForm());
    }

    $this->widgetSchema->setNameFormat('request_register_url[%s]');
  }

  public function validate($validator, $values, $arguments = array())
  {
    if (opToolkit::isMobileEmailAddress($values['mail_address']))
    {
      $mailValidator = new sfValidatorMobileEmail();
      $values['mobile_address'] = $mailValidator->clean($values['mail_address']);
      $mode = 'mobile';
    }
    else
    {
      $mailValidator = new opValidatorPCEmail();
      $values['pc_address'] = $mailValidator->clean($values['mail_address']);
      $mode = 'pc';
    }

    if (!opToolkit::isEnabledRegistration($mode))
    {
      throw new sfValidatorError($validator, 'invalid');
    }

    if (!empty($values['mobile_address']) && !$this->validateAddress('mobile_address', $values['mobile_address']))
    {
      $this->doNotSend = true;
    }
    if (!empty($values['pc_address']) && !$this->validateAddress('pc_address', $values['pc_address']))
    {
      $this->doNotSend = true;
    }

    return $values;
  }

  protected function validateAddress($configName, $configValue)
  {
    if ($config = Doctrine::getTable('MemberConfig')->retrieveByNameAndValue($configName, $configValue))
    {
      return false;
    }
    elseif ($config = Doctrine::getTable('MemberConfig')->retrieveByNameAndValue($configName.'_pre', $configValue))
    {
      $activation = opActivateBehavior::getEnabled();
      opActivateBehavior::disable();

      $this->member = $config->getMember();

      if ($activation)
      {
        opActivateBehavior::enable();
      }
    }

    return true;
  }

  public function sendMail()
  {
    if ($this->doNotSend)
    {
      return null;
    }

    $address = '';

    $member = $this->member;
    if (!$member)
    {
      $member = Doctrine::getTable('Member')->createPre();
    }

    if ($this->getValue('pc_address'))
    {
      $address = $this->getValue('pc_address');

      $member->setConfig('pc_address_pre', $address);
    }
    elseif ($this->getValue('mobile_address'))
    {
      $address = $this->getValue('mobile_address');

      $member->setConfig('mobile_address_pre', $address);
    }

    $token = $member->generateRegisterToken();

    $authMode = $this->getOption('authMode', null);
    if (!$authMode)
    {
      $authMode = sfContext::getInstance()->getUser()->getCurrentAuthMode();
    }
    $member->setConfig('register_auth_mode', $authMode);

    $params = array(
      'token'    => $token,
      'authMode' => 'MailAddress',
      'subject' => opConfig::get('sns_name').'招待状',
    );
    opMailSend::sendTemplateMail('notifyRegisterURL', $address, opConfig::get('admin_mail_address'), $params);
  }
}
