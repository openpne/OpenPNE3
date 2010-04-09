<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class GadgetTable extends opAccessControlDoctrineTable
{
  protected
    $results,
    $configs = array(),
    $gadgets = array(),
    $gadgetConfigList = array();

  public function getConfig()
  {
    if (!isset($this->configs['config']))
    {
      $this->configs['config'] = include(sfContext::getInstance()
        ->getConfiguration()
        ->getConfigCache()
        ->checkConfig('config/gadget_config.yml'));
    }
    return $this->configs['config'];
  }

  public function getGadgetLayoutConfig()
  {
    if (!isset($this->configs['layout']))
    {
      $this->configs['layout'] = include(sfContext::getInstance()
        ->getConfiguration()
        ->getConfigCache()
        ->checkConfig('config/gadget_layout_config.yml'));
    }
    return $this->configs['layout'];
  }

  public function getGadgetConfig($typesName)
  {
    if (!isset($this->configs['gadget'][$typesName]))
    {
      $filename = 'config/'.sfinflector::underscore($typesName);
      if ($typesName != 'gadget')
      {
        $filename .= '_gadget';
      }
      $filename .= '.yml';

      $configCache = sfContext::getInstance()->getConfiguration()->getConfigCache();
      $configCache->registerConfigHandler($filename, 'opGadgetConfigHandler');
      $this->configs['gadget'][$typesName] = include($configCache->checkConfig($filename));
    }
    return $this->configs['gadget'][$typesName];
  }

  protected function getTypes($typesName)
  {
    $types = array();
    $configs = $this->getConfig();
    $layoutConfigs = $this->getGadgetLayoutConfig();

    if (!isset($configs[$typesName]))
    {
      throw new Doctrine_Exception('Invalid types name');
    }
    if (isset($configs[$typesName]['layout']['choices']))
    {
      foreach ($configs[$typesName]['layout']['choices'] as $choice)
      {
        $types = array_merge($types, $layoutConfigs[$choice]);
      }
    }
    $types = array_merge($types, $layoutConfigs[$configs[$typesName]['layout']['default']]);
    $types = array_unique($types);

    if ($typesName !== 'gadget')
    {
      foreach ($types as &$type)
      {
        $type = $typesName.ucfirst($type);
      }
    }

    return $types;
  }

  public function clearGadgetsCache()
  {
    $files = sfFinder::type('file')
      ->name('*_gadgets.php')
      ->in(sfConfig::get('sf_root_dir').'/cache');
    foreach ($files as $file)
    {
      @unlink($file);
    }
    $this->gadgets = array();
    $this->gadgetConfigList = array();
  }

  public function retrieveGadgetsByTypesName($typesName)
  {
    if (isset($this->gadgets[$typesName]))
    {
      return $this->gadgets[$typesName];
    }

    if (sfConfig::get('op_is_enable_gadget_cache', true))
    {
      $dir = sfConfig::get('sf_app_cache_dir').'/config';
      $file = $dir.'/'.sfInflector::underscore($typesName)."_gadgets.php";
      if (is_readable($file))
      {
        $results = unserialize(file_get_contents($file));
        $this->gadgets[$typesName] = $results;
        return $results;
      }
    }

    $types = $this->getTypes($typesName);

    foreach($types as $type)
    {
      $results[$type] = $this->retrieveByType($type);
    }

    if (sfConfig::get('op_is_enable_gadget_cache', true))
    {
      if (!is_dir($dir))
      {
        @mkdir($dir, 0777, true);
      }
      file_put_contents($file, serialize($results));
    }

    $this->gadgets[$typesName] = $results;

    return $results;
  }

  public function retrieveByType($type)
  {
    $results = $this->getResults();

    return (isset($results[$type])) ? $results[$type] : null;
  }

  public function getGadgetsIds($type)
  {
    $_result = $this->createQuery()
      ->select('id')
      ->where('type = ?', $type)
      ->orderBy('sort_order')
      ->execute();

    $result = array();

    foreach ($_result as $value)
    {
      $result[] = $value->getId();
    }

    return $result;
  }

  protected function getResults()
  {
    if (empty($this->results))
    {
      $this->results = array();
      $objects = $this->createQuery()->orderBy('sort_order')->execute();
      foreach ($objects as $object)
      {
        $this->results[$object->type][] = $object;
      }
    }
    return $this->results;
  }

  public function getGadgetConfigListByType($type)
  {
    if (isset($this->gadgetConfigList[$type]))
    {
      return $this->gadgetConfigList[$type];
    }

    $configs = $this->getConfig();
    foreach ($configs as $key => $config)
    {
      if (in_array($type, $this->getTypes($key)))
      {
        $resultConfig = $this->getGadgetConfig($key);
        $this->gadgetConfigList[$type] = $resultConfig;
        return $resultConfig;
      }
    }

    $this->gadgetConfigList[$type] = array();
    return array();
  }

  public function appendRoles(Zend_Acl $acl)
  {
    return $acl
      ->addRole(new Zend_Acl_Role('anonymous'))
      ->addRole(new Zend_Acl_Role('everyone'), 'anonymous');
  }

  public function appendRules(Zend_Acl $acl, $resource = null)
  {
    $acl->allow('everyone', $resource, 'view');

    if (4 == $resource->getConfig('viewable_privilege'))
    {
      $acl->allow('anonymous', $resource, 'view');
    }

    return $acl;
  }
}
