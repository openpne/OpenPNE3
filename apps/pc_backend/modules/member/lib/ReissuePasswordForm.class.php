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

    $emailAddress = $this->member->getEmailAddress();
    $params = array(
      'mailAddress' => $emailAddress,
      'newPassword' => $this->plainPassword,
      'isMobile' => opToolkit::isMobileEmailAddress($emailAddress)
    );
    $this->sendConfirmMail($emailAddress, $params);
  }

  public function setPlainPassword($validator, $value, $arguments = array())
  {
    $this->plainPassword = trim($value['password']);
    return $value;
  }

  public function sendConfirmMail($to, $params = array())
  {
    $params['subject'] = opConfig::get('sns_name').' '.sfContext::getInstance()->getI18N()->__('New password has been issued');
    opMailSend::sendTemplateMail('reissuedPassword', $to, opConfig::get('admin_mail_address'), $params);
  }
}
