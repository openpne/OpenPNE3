<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class Gadget extends BaseGadget implements opAccessControlRecordInterface
{
  protected $list = null;

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

  protected function getGadgetConfigList()
  {
    if (null === $this->list)
    {
      $this->list = Doctrine::getTable('Gadget')->getGadgetConfigListByType($this->type);
    }
    return $this->list;
  }

  public function getComponentModule()
  {
    $list = $this->getGadgetConfigList();
    if (empty($list[$this->name]))
    {
      return false;
    }

    return $list[$this->name]['component'][0];
  }

  public function getComponentAction()
  {
    $list = $this->getGadgetConfigList();
    if (empty($list[$this->name]))
    {
      return false;
    }

    return $list[$this->name]['component'][1];
  }

  public function isEnabled()
  {
    $list = $this->getGadgetConfigList();
    if (empty($list[$this->name]))
    {
      return false;
    }

    $controller = sfContext::getInstance()->getController();
    if (!$controller->componentExists($this->getComponentModule(), $this->getComponentAction()))
    {
      return false;
    }

    $member = sfContext::getInstance()->getUser()->getMember();
    if (!$member)
    {
      $member = sfContext::getInstance()->getUser()->getMember(true);
    }

    if (!$member || !$this->isAllowed($member, 'view'))
    {
      return false;
    }

    return true;
  }

  public function getConfig($name)
  {
    $result = null;
    $list = $this->getGadgetConfigList();

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

  public function generateRoleId(Member $member)
  {
    if ($member instanceof opAnonymousMember)
    {
      return 'anonymous';
    }

    return 'everyone';
  }
}
