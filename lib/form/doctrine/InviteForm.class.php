<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * Invite form.
 *
 * @package    OpenPNE
 * @subpackage form
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class InviteForm extends MemberConfigPcAddressForm
{
  public function configure()
  {
    $this->setWidget('mail_address', new sfWidgetFormInputText());
    $this->setValidator('mail_address', new sfValidatorPass());

    if ($this->getOption('invited'))
    {
      $this->setWidget('message', new sfWidgetFormTextarea());
      $this->setValidator('message', new sfValidatorPass());
      $this->widgetSchema->setLabel('message', 'Message(Arbitrary)');
    }

    $callback = new sfValidatorCallback(array(
        'callback' => array($this, 'validate'),
    ));
    $callback->setMessage('invalid', 'invalid e-mail address');
    $this->validatorSchema->setPostValidator($callback);

    if (sfConfig::get('op_is_use_captcha', false))
    {
      $this->embedForm('captcha', new opCaptchaForm());
    }

    if ('mobile_frontend' === sfConfig::get('sf_app'))
    {
      opToolkit::appendMobileInputModeAttributesForFormWidget($this->getWidget('mail_address'), 'alphabet');
    }
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
      throw new sfValidatorError($validator, 'invalid');
    }
    if (!empty($values['pc_address']) && !$this->validateAddress('pc_address', $values['pc_address']))
    {
      throw new sfValidatorError($validator, 'invalid');
    }

    return $values;
  }

  protected function validateAddress($configName, $configValue)
  {
    $activation = opActivateBehavior::getEnabled();
    opActivateBehavior::disable();

    if ($config = Doctrine::getTable('MemberConfig')->retrieveByNameAndValue($configName, $configValue))
    {
      if ($config->getMember()->getIsActive() || !$config->getMember()->getConfig($configName.'_token'))
      {
        if ($activation)
        {
          opActivateBehavior::enable();
        }
        return false;
      }

      $this->member = $config->getMember();
    }
    elseif ($config = Doctrine::getTable('MemberConfig')->retrieveByNameAndValue($configName.'_pre', $configValue))
    {
      $this->member = $config->getMember();
    }

    if ($activation)
    {
      opActivateBehavior::enable();
    }
    return true;
  }

  public function saveConfig($name, $value)
  {
    if ('pc_address' === $name || 'mobile_address' === $name)
    {
      $this->savePreConfig($name, $value);

      $token = $this->member->generateRegisterToken();

      $this->sendConfirmMail($token, $value, array(
        'id'   => $this->member->getId(),
        'type' => $name,
      ));
    }
  }

  protected function sendConfirmMail($token, $to, $params = array())
  {
    $authMode = $this->getOption('authMode', null);
    if (!$authMode)
    {
      $authMode = sfContext::getInstance()->getUser()->getCurrentAuthMode();
    }

    $params = array(
      'token'    => $token,
      'authMode' => $authMode,
      'isMobile' => opToolkit::isMobileEmailAddress($to),
      'name'     => $this->getOption('invited') ? sfContext::getInstance()->getUser()->getMember()->getName() : null,
      'message'  => $this->getOption('invited') ? $this->getValue('message') : null,
      'subject' => opConfig::get('sns_name').'招待状',
    );
    opMailSend::sendTemplateMail('requestRegisterURL', $to, opConfig::get('admin_mail_address'), $params);
  }

  public function save()
  {
    $user = sfContext::getInstance()->getUser();
    $this->member->setInviteMemberId($user->getMemberId());

    parent::save();

    if ($this->getOption('is_link'))
    {
      $fromMemberId = $user->getMemberId();
      $toMemberId = $this->member->getId();
      $relation = Doctrine::getTable('MemberRelationship')->retrieveByFromAndTo($fromMemberId, $toMemberId);
      if (!$relation)
      {
        $relation = new MemberRelationship();
        $relation->setMemberIdFrom($fromMemberId);
        $relation->setMemberIdTo($toMemberId);
      }
      $relation->setFriend();
    }
  }
}
