<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opMessageSender
 *
 * @package    opMessagePlugin
 * @subpackage util
 * @author     Shogo Kawahara <kawahara@tejimaya.net>
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opMessageSender
{
  protected
    $toMembers = null,
    $options = array(),
    $subject = '',
    $body = '';

 /**
  * set to members
  *
  * @param  mixed $toMember a Member instance or array of Member instance
  * @return opMessageSender
  */
  public function setToMembers($toMembers)
  {
    if ($toMembers instanceof Member)
    {
      $this->toMembers = array($toMembers);
    }
    elseif (is_array($toMembers))
    {
      $this->toMembers = $toMembers;
    }
    else
    {
      throw new InvalidArgumentException();
    }

    return $this;
  }

 /**
  *
  * set to members
  *
  * @param  Member $toMember a Member instance
  * @return opMessageSender
  */ 
  public function setToMember(Member $toMember)
  {
    $this->setToMembers($toMember);
    return $this;
  }

 /**
  * set from member
  *
  * @param  Member $fromMember a member instance
  * @return opMessageSender
  */
  public function setFromMember(Member $fromMember)
  {
    $this->options['fromMember'] = $fromMember;
    return $this;
  }

 /**
  * set subject
  *
  * @param  string $subject
  * @return opMessageSender
  */
  public function setSubject($subject)
  {
    $this->subject = $subject;
    return $this;
  }

 /**
  * set body text
  *
  * @param string $body 
  * @return opMessageSender
  */
  public function setBody($body)
  {
    $this->body = $body;
    return $this;
  }

  public function setIdentifier($identifier)
  {
    $this->options['identifier'] = (int)$identifier;

    return $this;
  }

  public static function decorateBySpecifiedTemplate($templateName, $templateParams = array())
  {
    $templateName = '_'.$templateName;
    $view = new opGlobalPartialView(sfContext::getInstance(), 'superGlobal', $templateName, '');
    $view->setPartialVars($templateParams);

    return $view->render();
  }

 /**
  * set message type
  *
  * @param  string $type
  * @return opMessageSender
  */
  public function setMessageType($type)
  {
    $this->options['type'] = $type;
    return $this;
  }

 /**
  * send message
  */
  public function send()
  {
    $options = $this->options;
    $options['is_read'] = true;

    Doctrine::getTable('SendMessageData')->sendMessage($this->toMembers, $this->subject, $this->body, $options);
  }
}
