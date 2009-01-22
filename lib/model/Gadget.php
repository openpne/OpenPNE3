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
  public function save(PropelPDO $con = null)
  {
    if (!$this->getSortOrder())
    {
      $maxSortOrder = 0;

      $gadgets = GadgetPeer::retrieveByType($this->getType());
      $finalGadget = array_pop($gadgets);
      if ($finalGadget)
      {
        $maxSortOrder = $finalGadget->getSortOrder();
      }

      $this->setSortOrder($maxSortOrder + 10);
    }

    return parent::save($con);
  }

  public function getComponentModule()
  {
    $list = GadgetPeer::getGadgetConfigListByType($this->getType());

    if (empty($list[$this->getName()]))
    {
      return false;
    }

    return $list[$this->getName()]['component'][0];
  }

  public function getComponentAction()
  {
    $list = GadgetPeer::getGadgetConfigListByType($this->getType());

    if (empty($list[$this->getName()]))
    {
      return false;
    }

    return $list[$this->getName()]['component'][1];
  }

  public function isEnabled()
  {
    $list = GadgetPeer::getGadgetConfigListByType($this->getType());
    if (empty($list[$this->getName()]))
    {
      return false;
    }

    return true;
  }

  public function getConfig($name)
  {
    $result = null;
    $list = GadgetPeer::getGadgetConfigListByType($this->getType());

    $config = GadgetConfigPeer::retrieveByGadgetIdAndName($this->getId(), $name);
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
