<?php

/**
 * Copyright (C) 2005-2009 OpenPNE Project
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *  http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

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
