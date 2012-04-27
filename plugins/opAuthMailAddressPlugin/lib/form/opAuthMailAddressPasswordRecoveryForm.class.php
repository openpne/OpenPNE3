<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opAuthMailAddressPasswordRecoveryForm
 *
 * @package    OpenPNE
 * @subpackage form
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opAuthMailAddressPasswordRecoveryForm extends BaseForm
{
  protected $member = null;

  public function configure()
  {
    $config = sfConfig::get('openpne_member_config');
    $choices = $config['secret_question']['Choices'];

    $this->setWidgets(array(
      'mail_address' => new sfWidgetFormInput(),
      'secret_question' => new sfWidgetFormChoice(array('choices' => $choices)),
      'secret_answer' => new sfWidgetFormInput(),
    ));

    $this->setValidators(array(
      'mail_address' => new sfValidatorEmail(),
      'secret_question' => new sfValidatorChoice(array('choices' => array_keys($choices))),
      'secret_answer' => new opValidatorString(),
    ));

    $this->widgetSchema->setLabels(array(
      'secret_question' => 'Your Secret Question',
      'secret_answer' => 'Your Answer for the Question',
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorCallback(array(
        'callback' => array($this, 'checkSecretQuestion'),
      ))
    );

    $this->widgetSchema->setNameFormat('password_recovery[%s]');
  }

  public function checkSecretQuestion($validator, $values, $arguments = array())
  {
    $configName = (opToolkit::isMobileEmailAddress($values['mail_address'])) ? 'mobile_address' : 'pc_address';
    $memberConfig = Doctrine::getTable('MemberConfig')->findOneByNameAndValue($configName, $values['mail_address']);
    if (!$memberConfig)
    {
      throw new sfValidatorError($validator, 'invalid');
    }

    $this->member = $memberConfig->Member;

    if ($values['secret_question'] != $this->member->getConfig('secret_question'))
    {
      throw new sfValidatorError($validator, 'invalid');
    }

    // To keep backward compatibility with OpenPNE 1.x, your answer is checked as Shift-JIS too.
    if (!in_array($this->member->getConfig('secret_answer'), array(md5($values['secret_answer']), md5(mb_convert_encoding($values['secret_answer'], 'SJIS-win', 'UTF-8')))))
    {
      throw new sfValidatorError($validator, 'invalid');
    }

    return $values;
  }

  public function saveConfig($name, $value)
  {
    if ('pc_address' === $name || 'mobile_address' === $name)
    {
      $this->savePreConfig($name, $value);
      $memberConfig = Doctrine::getTable('MemberConfig')->retrieveByNameAndMemberId($name.'_token', $this->member->getId(), true);
      $token = $memberConfig->getValue();
      $this->sendConfirmMail($token, $value, array(
        'id'   => $this->member->getId(),
        'type' => $name,
      ));
    }
  }

  public function sendMail()
  {
    $token = md5(opToolkit::generatePasswordString());
    $this->member->setConfig('password_recovery_token', $token);
    $params = array(
      'token' => $token,
      'id' => $this->member->id,
      'subject' => '【'.opConfig::get('sns_name').'】パスワード再設定用URL発行のお知らせ',
    );

    sfOpenPNEMailSend::sendTemplateMail('passwordRecovery', $this->member->getEMailAddress(), opConfig::get('admin_mail_address'), $params);
  }
}
