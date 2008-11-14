<?php

/**
 * sfOpenPNEMailSend
 *
 * @package    OpenPNE
 * @subpackage util
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class sfOpenPNEMailSend
{
  public $subject = '';
  public $body = '';

  public function setSubject($subject)
  {
    $this->subject = $subject;
  }

  public function setTemplate($template, $params = array())
  {
    $body = $this->getCurrentAction()->getPartial($template, $params);
    $this->body = $body;
  }

  public function send($to, $from)
  {
    return self::execute($this->subject, $to, $from, $this->body);
  }

  public static function execute($subject, $to, $from, $body)
  {
    $swift = new Swift(new Swift_Connection_NativeMail());

    $msg = new Swift_Message(
      mb_convert_encoding($subject, 'JIS', 'UTF-8'),
      mb_convert_encoding($body, 'JIS', 'UTF-8'),
      'text/plain', '7bit', 'iso-2022-jp'
    );
    $msg->headers->setCharset('ISO-2022-JP');

    return $swift->send($msg, $to, $from);
  }

 /**
  * Gets the current action instance.
  *
  * @return sfAction
  */
  protected function getCurrentAction()
  {
    return sfContext::getInstance()->getController()->getActionStack()->getLastEntry()->getActionInstance();
  }
}
