<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opDynamicAclRoute
 *
 * @package    OpenPNE
 * @subpackage routing
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opDynamicAclRoute extends sfDoctrineRoute
{
  protected
    $acl = null;

  public function getAcl()
  {
    return $this->acl;
  }

  public function getObject()
  {
    $result = parent::getObject();

    if (!$this->acl->isAllowed($this->getCurrentMemberId(), null, $this->options['privilege']))
    {
      throw new sfError404Exception('You are not allowed access to this resource.');
    }

    return $result;
  }

  protected function getObjectForParameters($parameters)
  {
    $result = parent::getObjectForParameters($parameters);

    $this->acl = call_user_func($this->getAclBuilderName().'::buildResource', $result, $this->getTargetMemberList());

    return $result;
  }

  protected function getAclBuilderName()
  {
    return 'op'.$this->options['model'].'AclBuilder';
  }

  protected function getCurrentMemberId()
  {
    $result = 0;
    $user = sfContext::getInstance()->getUser();

    if (!is_null($user) && $user instanceof sfOpenPNESecurityUser)
    {
      $result = $user->getMemberId();
    }

    return $result;
  }

  protected function getTargetMemberList()
  {
    $result = array();
    $user = sfContext::getInstance()->getUser();

    if (!is_null($user) && $user instanceof sfOpenPNESecurityUser)
    {
      $result[] = $user->getMember();
    }

    return $result;
  }
}
