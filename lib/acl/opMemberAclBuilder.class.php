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
    $acl = Doctrine::getTable('Member')->appendRoles($acl);

    return $acl;
  }

  static public function buildResource($resource, $targetMembers)
  {
    $acl = self::getAcl();

    foreach ($targetMembers as $member)
    {
      $roleString = $resource->generateRoleId($member);

      $role = new Zend_Acl_Role($member);
      $acl->addRole($role, $roleString);

      $role = new Zend_Acl_Role($member->id);
      $acl->addRole($role, $roleString);
    }

    $acl = Doctrine::getTable('Member')->appendRules($acl);

    return $acl;
  }
}
