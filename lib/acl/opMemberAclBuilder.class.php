<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opMemberAclBuilder
 *
 * @package    OpenPNE
 * @subpackage acl
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opMemberAclBuilder extends opAclBuilder
{
  static protected
    $resource = array();

  static public function getAcl()
  {
    $acl = new Zend_Acl();
    $acl->addRole(new Zend_Acl_Role('everyone'));
    $acl->addRole(new Zend_Acl_Role('blocked'), 'everyone');
    $acl->addRole(new Zend_Acl_Role('self'), 'everyone');

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
      $relation = Doctrine::getTable('MemberRelationship')
        ->retrieveByFromAndTo($resource->id, $member->id);

      $role = new Zend_Acl_Role($member->getId());
      if ($resource->getId() === $member->getId())
      {
        $acl->addRole($role, 'self');
      }
      elseif ($relation && $relation->getIsAccessBlock())
      {
        $acl->addRole($role, 'blocked');
      }
      else
      {
        $acl->addRole($role, 'everyone');
      }
    }

    $acl->deny('blocked', null, 'view');
    $acl->allow('everyone', null, 'view');

    return $acl;
  }
}
