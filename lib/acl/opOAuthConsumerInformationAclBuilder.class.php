<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opOAuthConsumerInformationAclBuilder
 *
 * @package    OpenPNE
 * @subpackage acl
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opOAuthConsumerInformationAclBuilder extends opAclBuilder
{
  static protected
    $resource = array();

  static public function getAcl()
  {
    $acl = new Zend_Acl();
    $acl->addRole(new Zend_Acl_Role('everyone'));
    $acl->addRole(new Zend_Acl_Role('creator'), 'everyone');

    $acl->addRole(new Zend_Acl_Role('user'), 'everyone');
    $acl->addRole(new Zend_Acl_Role('creator_user'), 'creator');

    return $acl;
  }

  static public function buildResource($resource, $targetMembers)
  {
    if (isset(self::$resource[$resource->getId()]))
    {
      return self::$resource[$resource->getId()];
    }

    $acl = self::getAcl();

    foreach ($targetMembers as $member)
    {
      $role = new Zend_Acl_Role($member->getId());
      if ($resource->getMemberId() === $member->getId())
      {
        $name = 'creator';
        if ($resource->getOAuthMemberAccessToken($member->getId()))
        {
          $name .= '_user';
        }
        $acl->addRole($role, $name);
      }
      else
      {
        if ($resource->getOAuthMemberAccessToken($member->getId()))
        {
          $acl->addRole($role, 'user');
        }
        else
        {
          $acl->addRole($role, 'everyone');
        }
      }
    }

    $acl->allow('everyone', null, 'view');
    $acl->allow('everyone', null, 'create');
    $acl->allow('creator', null, 'edit');
    $acl->allow('creator', null, 'delete');

    $acl->allow('user', null, 'use');
    $acl->allow('creator_user', null, 'use');

    self::$resource[$resource->getId()] = $acl;

    return $acl;
  }
}
