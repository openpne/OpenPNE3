<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opCommunityTopicAclBuilder
 *
 * @package    OpenPNE
 * @subpackage acl
 * @author     Eitarow Fukamachi <fukamachi@tejimaya.net>
 */
class opCommunityAclBuilder extends opAclBuilder
{
  static protected
    $resource = array();

  static public function clearCache()
  {
    self::$resource = array();
  }

  static public function getAcl()
  {
    $acl = new Zend_Acl();
    $acl->addRole(new Zend_Acl_Role('alien'));
    $acl->addRole(new Zend_Acl_Role('guest'), 'alien');
    $acl->addRole(new Zend_Acl_Role('member'), 'guest');
    $acl->addRole(new Zend_Acl_Role('subadmin'), 'member');
    $acl->addRole(new Zend_Acl_Role('admin'), 'subadmin');

    $acl->allow('subadmin', null, 'edit');
    $acl->allow('subadmin', null, 'delete');

    return $acl;
  }

  static public function buildResource($resource, $targetMembers)
  {
    if (isset(self::$resource[$resource->getId()]))
    {
      return self::$resource[$resource->getId()];
    }

    $acl = self::getAcl();

    if ('public' === $resource->getConfig('public_flag'))
    {
      $acl->allow('guest', null, 'view');
    }
    else if ('open' === $resource->getConfig('public_flag'))
    {
      $acl->allow('alien', null, 'view');
    }
    else if ('auth_commu_member' ===  $resource->getConfig('public_flag'))
    {
      $acl->allow('member', null, 'view');
    }
    else
    {
      $event = new sfEvent(sfContext::getInstance(), 'op_acl.unknown_community_public_flag', array('public_flag' => $resource->getConfig('public_flag')));
      sfContext::getInstance()->getEventDispatcher()->filter($event, $acl);
      $acl = $event->getReturnValue();
    }

    foreach ($targetMembers as $member)
    {
      if ($member)
      {
        $role = new Zend_Acl_Role($member->getId());
        if ($resource->isAdmin($member->getId()))
        {
          $acl->addRole($role, 'admin');
        }
        else if ($resource->isPrivilegeBelong($member->getId()))
        {
          $acl->addRole($role, 'member');
        }
        else
        {
          $acl->addRole($role, 'guest');
        }
      }
    }

    self::$resource[$resource->getId()] = $acl;

    return $acl;
  }
}

