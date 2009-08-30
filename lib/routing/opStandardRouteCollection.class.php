<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opStandardRouteCollection
 *
 * @package    OpenPNE
 * @subpackage routing
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opStandardRouteCollection extends sfDoctrineRouteCollection
{
  public function __construct(array $options)
  {
    if (!empty($options['is_acl']))
    {
      $options['route_class'] = 'opDynamicAclRoute';
    }

    parent::__construct($options);
  }

  protected function getRouteForCollection($action, $methods)
  {
    return new $this->routeClass(
      sprintf('%s/%s', $this->options['prefix_path'], $action),
      array('module' => $this->options['module'], 'action' => $action, 'sf_format' => 'html'),
      array_merge($this->options['requirements'], array('sf_method' => $methods)),
      array('model' => $this->options['model'], 'type' => 'list', 'method' => $this->options['model_methods']['list'], 'privilege' => $this->getPrivilege($action))
    );
  }

  protected function getRouteForObject($action, $methods)
  {
    return new $this->routeClass(
      sprintf('%s/:%s/%s', $this->options['prefix_path'], $this->options['column'], $action),
      array('module' => $this->options['module'], 'action' => $action, 'sf_format' => 'html'),
      array_merge($this->options['requirements'], array('sf_method' => $methods)),
      array('model' => $this->options['model'], 'type' => 'object', 'method' => $this->options['model_methods']['object'], 'privilege' => $this->getPrivilege($action))
    );
  }

  protected function getRouteForList()
  {
    return new $this->routeClass(
      sprintf('%s', $this->options['prefix_path']),
      array('module' => $this->options['module'], 'action' => $this->getActionMethod('list'), 'sf_format' => 'html'),
      array_merge($this->options['requirements'], array('sf_method' => 'get')),
      array('model' => $this->options['model'], 'type' => 'list', 'method' => $this->options['model_methods']['list'], 'privilege' => $this->getPrivilege('list'))
    );
  }

  protected function getRouteForNew()
  {
    return new $this->routeClass(
      sprintf('%s/%s', $this->options['prefix_path'], $this->options['segment_names']['new']),
      array('module' => $this->options['module'], 'action' => $this->getActionMethod('new'), 'sf_format' => 'html'),
      array_merge($this->options['requirements'], array('sf_method' => 'get')),
      array('model' => $this->options['model'], 'type' => 'object', 'privilege' => $this->getPrivilege('create'))
    );
  }

  protected function getRouteForCreate()
  {
    return new $this->routeClass(
      sprintf('%s', $this->options['prefix_path']),
      array('module' => $this->options['module'], 'action' => $this->getActionMethod('create'), 'sf_format' => 'html'),
      array_merge($this->options['requirements'], array('sf_method' => 'post')),
      array('model' => $this->options['model'], 'type' => 'object', 'privilege' => $this->getPrivilege('create'))
    );
  }

  protected function getRouteForShow()
  {
    return new $this->routeClass(
      sprintf('%s/:%s', $this->options['prefix_path'], $this->options['column']),
      array('module' => $this->options['module'], 'action' => $this->getActionMethod('show'), 'sf_format' => 'html'),
      array_merge($this->options['requirements'], array('sf_method' => 'get')),
      array('model' => $this->options['model'], 'type' => 'object', 'method' => $this->options['model_methods']['object'], 'privilege' => $this->getPrivilege('show'))
    );
  }

  protected function getRouteForEdit()
  {
    return new $this->routeClass(
      sprintf('%s/:%s/%s', $this->options['prefix_path'], $this->options['column'], $this->options['segment_names']['edit']),
      array('module' => $this->options['module'], 'action' => $this->getActionMethod('edit'), 'sf_format' => 'html'),
      array_merge($this->options['requirements'], array('sf_method' => 'get')),
      array('model' => $this->options['model'], 'type' => 'object', 'method' => $this->options['model_methods']['object'], 'privilege' => $this->getPrivilege('edit'))
    );
  }

  protected function getRouteForUpdate()
  {
    return new $this->routeClass(
      sprintf('%s/:%s', $this->options['prefix_path'], $this->options['column']),
      array('module' => $this->options['module'], 'action' => $this->getActionMethod('update'), 'sf_format' => 'html'),
      array_merge($this->options['requirements'], array('sf_method' => array('put', 'post'))),
      array('model' => $this->options['model'], 'type' => 'object', 'method' => $this->options['model_methods']['object'], 'privilege' => $this->getPrivilege('edit'))
    );
  }

  protected function getRouteForDelete()
  {
    return new $this->routeClass(
      sprintf('%s/:%s/delete', $this->options['prefix_path'], $this->options['column']),
      array('module' => $this->options['module'], 'action' => $this->getActionMethod('delete'), 'sf_format' => 'html'),
      array('sf_method' => array('post', 'delete')),
      array('model' => $this->options['model'], 'type' => 'object', 'method' => $this->options['model_methods']['object'], 'privilege' => $this->getPrivilege('delete'))
    );
  }

  protected function getRouteForDeleteConfirm()
  {
    return new $this->routeClass(
      sprintf('%s/:%s/%s', $this->options['prefix_path'], $this->options['column'], 'delete'),
      array('module' => $this->options['module'], 'action' => $this->getActionMethod('deleteConfirm'), 'sf_format' => 'html'),
      array('sf_method' => array('get')),
      array('model' => $this->options['model'], 'type' => 'object', 'method' => $this->options['model_methods']['object'], 'privilege' => $this->getPrivilege('delete'))
    );
  }

  protected function getPrivilege($action)
  {
    $privileges = array(
      'list'           => 'view',
      'show'           => 'view',
      'new'            => 'create',
      'create'         => 'create',
      'edit'           => 'edit',
      'update'         => 'edit',
      'delete'         => 'delete',
      'deleteConfirm'  => 'delete',
    );

    if (isset($this->options['privileges']))
    {
      $privileges = array_merge($privileges, (array)$this->options['privileges']);
    }

    if (isset($privileges[$action]))
    {
      return $privileges[$action];
    }

    return null;
  }

  protected function getDefaultActions()
  {
    $actions = parent::getDefaultActions();
    $actions[] = 'deleteConfirm';

    return $actions;
  }
}
