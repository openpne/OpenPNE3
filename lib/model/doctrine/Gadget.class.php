<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class Gadget extends BaseGadget
{
  public function preSave($event)
  {
    if (!$this->getSortOrder())
    {
      $maxSortOrder = 0;

      $gadgets = Doctrine::getTable('Gadget')->retrieveByType($this->getType());
      if ($gadgets)
      {
        $finalGadget = array_pop($gadgets);
        if ($finalGadget)
        {
          $maxSortOrder = $finalGadget->getSortOrder();
        }
      }

      $this->setSortOrder($maxSortOrder + 10);
    }
  }

  public function getComponentModule()
  {
    $list = Doctrine::getTable('Gadget')->getGadgetConfigListByType($this->type);
    if (empty($list[$this->name]))
    {
      return false;
    }

    return $list[$this->name]['component'][0];
  }

  public function getComponentAction()
  {
    $list = Doctrine::getTable('Gadget')->getGadgetConfigListByType($this->type);
    if (empty($list[$this->name]))
    {
      return false;
    }

    return $list[$this->name]['component'][1];
  }

  public function isEnabled()
  {
    $list = Doctrine::getTable('Gadget')->getGadgetConfigListByType($this->type);
    if (empty($list[$this->name]))
    {
      return false;
    }

    $controller = sfContext::getInstance()->getController();
    if (!$controller->componentExists($this->getComponentModule(), $this->getComponentAction()))
    {
      return false;
    }

    return true;
  }

  public function getConfig($name)
  {
    $result = null;
    $list = Doctrine::getTable('Gadget')->getGadgetConfigListByType($this->getType());

    $config = Doctrine::getTable('GadgetConfig')->retrieveByGadgetIdAndName($this->getId(), $name);
    if ($config)
    {
      $result = $config->getValue();
    }
    elseif (isset($list[$this->getName()]['config'][$name]['Default']))
    {
      $result = $list[$this->getName()]['config'][$name]['Default'];
    }

    return $result;
  }
}
