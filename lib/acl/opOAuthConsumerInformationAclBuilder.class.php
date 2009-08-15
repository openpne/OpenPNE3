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
        $acl->addRole($role, 'creator');
      }
      else
      {
        $acl->addRole($role, 'everyone');
      }
    }

    $acl->allow('everyone', null, 'view');
    $acl->allow('everyone', null, 'create');
    $acl->allow('creator', null, 'edit');
    $acl->allow('creator', null, 'delete');

    self::$resource[$resource->getId()] = $acl;

    return $acl;
  }
}
