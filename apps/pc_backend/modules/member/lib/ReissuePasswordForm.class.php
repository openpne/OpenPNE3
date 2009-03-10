<?php 

class ReissuePasswordForm extends MemberConfigPasswordForm
{
  protected $plainPassword = null;

  public function configure()
  {
    $this->mergePreValidator(new sfValidatorCallback(array(
      'callback' => array($this, 'setPlainPassword')
    )));
  }

  public function save()
  {
    parent::save();

    $emailAddresses = $this->member->getEmailAddresses();

    foreach ($emailAddresses as $emailAddress)
    {
      $params = array(
        'mailAddress' => $emailAddress,
        'newPassword' => $this->plainPassword,
        'isMobile' => opToolkit::isMobileEmailAddress($emailAddress)
      );

      $this->sendConfirmMail($emailAddress, $params);
    }
  }

  public function setPlainPassword($validator, $value, $arguments = array())
  {
    $this->plainPassword = trim($value['password']);
    return $value;
  }

  public function sendConfirmMail($to, $params = array())
  {
    $mail = new sfOpenPNEMailSend();
    $mail->setSubject(opConfig::get('sns_name').' '.sfContext::getInstance()->getI18N()->__('パスワード再発行のお知らせ'));
    $mail->setTemplate('global/reissuedPasswordMail', $params);
    $mail->send($to, opConfig::get('admin_mail_address'));
  }
}
