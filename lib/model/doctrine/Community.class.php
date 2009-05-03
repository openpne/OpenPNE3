<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class Community extends BaseCommunity
{
  public function getImageFileName()
  {
    if ($this->File)
    {
      return $this->File->name;
    }

    return '';
  }

  public function getConfigs()
  {
    $configs = sfConfig::get('openpne_community_config');

    $myConfigs = Doctrine::getTable('CommunityConfig')->retrievesByCommunityId($this->id);

    $result = array();

    // initialize
    foreach ($configs as $key => $config)
    {
      $result[$config['Caption']] = '';
      if (isset($config[$key]['Default']))
      {
        $result[$config['Caption']] = $config[$key]['Default'];
      }
    }
    
    // set my configure
    foreach ($myConfigs as $myConfig)
    {
      $name = $myConfig->getName();
      if (isset($configs[$name]))
      {
        switch ($configs[$name]['FormType'])
        {
          case 'checkbox' :
          // FIXME
          case 'radio' :
          case 'select' :
            $value = $myConfig->getValue();
            if (isset($configs[$name]['Choices'][$value]))
            {
              $i18n = sfContext::getInstance()->getI18N();
              $result[$configs[$name]['Caption']] = $i18n->__($configs[$name]['Choices'][$value]);
            }
            break;
          default :
            $result[$configs[$name]['Caption']] = $myConfig->getValue();
        }
        $configs[$myConfig->getName()] = $myConfig->getValue();
      }
    }

    return $result;
  }

  public function getConfig($configName)
  {
    $config = Doctrine::getTable('CommunityConfig')->retrieveByNameAndCommunityId($configName, $this->getId());

    if (!$config)
    {
      return null;
    }

    return $config->value;
  }

  public function getMembers($limit = null, $isRandom = false)
  {
    $q = Doctrine::getTable('Member')->createQuery()
        ->where('cm.community_id = ?', $this->id)
        ->andWhere('cm.position <> ?', 'pre')
        ->leftJoin('Member.CommunityMember cm');

    if (!is_null($limit))
    {
      $q->limit($limit);
    }

    if ($isRandom)
    {
      $expr = new Doctrine_Expression('RANDOM()');
      $q->orderBy($expr);
    }

    return $q->execute();
  }

  public function getAdminMember()
  {
    return Doctrine::getTable('CommunityMember')->getCommunityAdmin($this->getId())->getMember();
  }

  public function checkPrivilegeBelong($memberId)
  {
    if (!$this->isPrivilegeBelong($memberId))
    {
      throw new opPrivilegeException('fail');
    }
  }

  public function isPrivilegeBelong($memberId)
  {
    return Doctrine::getTable('CommunityMember')->isMember($memberId, $this->id);
  }

  public function isAdmin($memberId)
  {
    return Doctrine::getTable('CommunityMember')->isAdmin($memberId, $this->id);
  }

  public function countCommunityMembers()
  {
    return $this->getMembers()->count();
  }

  public function getNameAndCount($format = '%s (%d)')
  {
    return sprintf($format, $this->getName(), $this->countCommunityMembers());
  }
}
