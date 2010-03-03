<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opMailRequest
 *
 * @package    OpenPNE
 * @subpackage request
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opMailRequest extends opWebRequest
{
  static protected
    $mailMessage = null;

  static public function setMailMessage($mailMessage)
  {
    self::$mailMessage = $mailMessage;
  }

  public function getPathInfo()
  {
    if (empty(self::$mailMessage))
    {
      throw new LogicException('You must specify the email message.');
    }

    $pieces = explode('@', self::$mailMessage->to);

    return array_shift($pieces);
  }

  public function getPathInfoArray()
  {
    if (!$this->pathInfoArray)
    {
      $this->pathInfoArray = array_merge(parent::getPathInfoArray(), self::$mailMessage->getHeaders());
    }

    return $this->pathInfoArray;
  }

  public function getRequestContext()
  {
    return array_merge(parent::getRequestContext(), array(
      'to_address'   => self::$mailMessage->to,
      'from_address' => self::$mailMessage->from,
    ));
  }

  public function getMailMessage()
  {
    return self::$mailMessage;
  }
}
