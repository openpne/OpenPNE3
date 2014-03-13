<?php

/**
* Copyright 2010 Kousuke Ebihara
*
* Licensed under the Apache License, Version 2.0 (the "License");
* you may not use this file except in compliance with the License.
* You may obtain a copy of the License at
*
* http://www.apache.org/licenses/LICENSE-2.0
*
* Unless required by applicable law or agreed to in writing, software
* distributed under the License is distributed on an "AS IS" BASIS,
* WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
* See the License for the specific language governing permissions and
* limitations under the License.
*/

/**
 * PluginPluginReleaseTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    opPluginChannelServerPlugin
 * @subpackage model
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class PluginPluginReleaseTable extends opAccessControlDoctrineTable
{
  public function getPager($id, $page = 1, $size = 20)
  {
    $q = Doctrine::getTable('PluginRelease')->createQuery()
      ->where('package_id = ?', $id);

    $pager = new sfDoctrinePager('PluginPackage', $size);
    $pager->setQuery($q);
    $pager->setPage($page);
    $pager->init();

    return $pager;
  }

  public function createByPackageInfo(array $info, File $file, $memberId, $xml)
  {
    return Doctrine::getTable('PluginRelease')->create(array(
      'version'   => $info['version'],
      'stability' => $info['stability']['release'],
      'file_id'   => $file->id,
      'member_id' => $memberId,
      'package_definition' => $xml,
    ));
  }

  public function appendRoles(Zend_Acl $acl)
  {
    return $acl
      ->addRole(new Zend_Acl_Role('anonymous'))
      ->addRole(new Zend_Acl_Role('sns_member'), 'anonymous')
      ->addRole(new Zend_Acl_Role('contributor'), 'sns_member')
      ->addRole(new Zend_Acl_Role('developer'), 'contributor')
      ->addRole(new Zend_Acl_Role('lead'), 'developer');
  }

  public function appendRules(Zend_Acl $acl, $resource = null)
  {
    return $acl
      ->allow('lead', $resource, 'add')
      ->allow('lead', $resource, 'delete')
    ;
  }
}
