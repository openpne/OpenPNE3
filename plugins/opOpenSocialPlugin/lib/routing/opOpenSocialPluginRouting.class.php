<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opOpenSocialPluginRouting
 *
 * @package    opOpenSocialPlugin
 * @subpackage routing
 * @author     Shogo Kawahara <kawahara@tejimaya.net>
 */
class opOpenSocialPluginRouting
{
  static public function listenToRoutingLoadConfigurationEvent(sfEvent $event)
  {
    $routing = $event->getSubject();
    
    $routes = array(
      'application' => new sfRoute(
        '/application',
        array('module' => 'application', 'action' => 'list')
      ),
      'application_list' => new sfDoctrineRoute(
        '/application/list/:id',
        array('module' => 'application', 'action' => 'list'),
        array('id' => '\d+'),
        array('model' => 'Member', 'type' => 'object')
      ),
      'my_application_list' => new sfRoute(
        '/application/list',
        array('module' => 'application', 'action' => 'list')
      ),
      'application_setting' => new sfDoctrineRoute(
        '/application/setting/:id',
        array('module' => 'application', 'action' => 'setting'),
        array('id' => '\d+', 'sf_method' => array('get', 'post')),
        array('model' => 'MemberApplication', 'type' => 'object')
      ),
      'application_gallery' => new sfRoute(
        '/application/gallery',
        array('module' => 'application', 'action' => 'gallery')
      ),
      'application_canvas' => new sfDoctrineRoute(
        '/application/canvas/:id',
        array('module' => 'application', 'action' => 'canvas'),
        array('id' => '\d+'),
        array('model' => 'MemberApplication', 'type' => 'object')
      ),
      'application_add' => new sfDoctrineRoute(
        '/application/add/:id',
        array('module' => 'application', 'action' => 'add'),
        array('id' => '\d+', 'sf_method' => array('get', 'post')),
        array('model' => 'Application', 'type' => 'object')
      ),
      'application_remove' => new sfDoctrineRoute(
        '/application/remove/:id',
        array('module' => 'application', 'action' => 'remove'),
        array('id' => '\d+', 'sf_method' => array('post', 'get')),
        array('model' => 'MemberApplication', 'type' => 'object')
      ),
      'application_info' => new sfDoctrineRoute(
        '/application/info/:id',
        array('module' => 'application', 'action' => 'info'),
        array('id' => '\d+'),
        array('model' => 'Application', 'type' => 'object')
      ),
      'application_member' => new sfDoctrineRoute(
        '/application/member/:id',
        array('module' => 'application', 'action' => 'member'),
        array('id' => '\d+'),
        array('model' => 'Application', 'type' => 'object')
      ),
      'application_install' => new sfRoute(
        '/application/install',
        array('module' => 'application', 'action' => 'install'),
        array('sf_method' => array('get', 'post'))
      ),
      'application_installed_list' => new sfRoute(
        '/application/installedList',
        array('module' => 'application', 'action' => 'installedList')
      ),
      'application_update' => new sfDoctrineRoute(
        '/application/update/:id',
        array('module' => 'application', 'action' => 'update'),
        array('id' => '\d+', 'sf_method' => 'post'),
        array('model' => 'Application', 'type' => 'object')
      ),
      'application_sort' => new sfRoute(
        '/application/sort',
        array('module' => 'application', 'action' => 'sort'),
        array('sf_method' => array('post'))
      ),
      'application_nodefaults' => new sfRoute(
        '/application/*',
        array('module' => 'default', 'action' => 'error')
      ),
    );

    $routes = array_reverse($routes);
    foreach ($routes as $name => $route)
    {
      $routing->prependRoute($name, $route);
    }
  }
}
