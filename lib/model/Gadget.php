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
  protected function getConfigList()
  {
    $mobileTypes = array('mobileTop', 'mobileContents', 'mobileBottom');
    $sideBannerTypes = array('sideBannerContents');
    if (in_array($this->getType(), $mobileTypes))
    {
      $list = sfConfig::get('op_mobile_gadget_list');
    }
    elseif(in_array($this->getType(), $sideBannerTypes))
    {
      return sfConfig::get('op_side_banner_gadget_list');
    }
    return sfConfig::get('op_gadget_list');
  }

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
    $list = $this->getConfigList();

    if (empty($list[$this->getName()]))
    {
      return false;
    }

    return $list[$this->getName()]['component'][0];
  }

  public function getComponentAction()
  {
    $list = $this->getConfigList();

    if (empty($list[$this->getName()]))
    {
      return false;
    }

    return $list[$this->getName()]['component'][1];
  }

  public function isEnabled()
  {
    $list = $this->getConfigList();
    if (empty($list[$this->getName()]))
    {
      return false;
    }

    return true;
  }

  public function getConfig($name)
  {
    $result = null;
    $list = $this->getConfigList();

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
