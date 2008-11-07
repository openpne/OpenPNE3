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
}
