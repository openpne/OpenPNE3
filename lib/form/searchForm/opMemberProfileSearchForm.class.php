<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opMemberProfileSearchForm
 *
 * @package    OpenPNE
 * @subpackage form
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opMemberProfileSearchForm extends sfForm
{
  protected static $profileFieldPrefix = 'profile_';
 
  protected function getProfiles()
  {
    return Doctrine::getTable('Profile')->retrievesAll();
  }
  
  public function configure()
  {
    $this->disableCSRFProtection();

    $widgets = array();
    $validators = array();

    if ($this->getOption('use_id'))
    {
      $widgets += array('id' => new sfWidgetFormInput());
      $validators += array('id' => new sfValidatorPass());
    }

    $widgets += array('name' => new sfWidgetFormInput());
    $validators += array('name' => new opValidatorSearchQueryString(array('required' => false)));

    foreach ($this->getProfiles() as $profile)
    {
      $widgets[self::$profileFieldPrefix.$profile->getName()] = opFormItemGenerator::generateSearchWidget($profile->toArray(), $profile->getOptionsArray());
      $validators[self::$profileFieldPrefix.$profile->getName()] = new sfValidatorPass();
    }

    $this->setWidgets($widgets);
    $this->setValidators($validators);

    $this->widgetSchema->setLabel('name', 'Nickname');

    $this->widgetSchema->setNameFormat('member[%s]');
  }

  protected function addIdColumnQuery(Doctrine_Query $query, $value)
  {
    if (!empty($value))
    {
      $query->andWhere('id = ?', $value);
    }
  }

  protected function addNameColumnQuery(Doctrine_Query $query, $value)
  {
    if (!empty($value))
    {
      if (is_array($value))
      {
        foreach ($value as $v)
        {
          $query->addWhere('name LIKE ?', '%'.$v.'%');
        }
      }
      else
      {
        if (!empty($value))
        {
          $query->addWhere('name LIKE ?', '%'.$values.'%');
        }
      }
    }
  }

  public function getQuery()
  {
    $isWhere = false;
    $ids = null;
    $q = Doctrine::getTable('Member')->createQuery();

    if ($this->getOption('use_id'))
    {
      $this->addIdColumnQuery($q, $this->getValue('id'));
    }

    $this->addNameColumnQuery($q, $this->getValue('name'));

    $profileValues = array();
    foreach ($this->getProfiles() as $profile)
    {
      $key = $profile->getName();
      $value = $this->getValue(self::$profileFieldPrefix.$key);

      if (is_array($value))
      {
        $isEmpty = true;
        foreach ($value as $v)
        {
          if(!empty($v))
          {
            $isEmpty = false;
          }
        }
        if ($isEmpty)
        {
          $value = null;
        }
      }
      if (!empty($value))
      {
        $profileValues[$key] = $value;
        $isWhere = true;
      }
    }

    $ids = Doctrine::getTable('MemberProfile')->searchMemberIds($profileValues, $ids);

    if ($isWhere)
    {
      if (!count($ids))
      {
        $ids[] = 0;
      }
      $q->whereIn('id', $ids);
    }

    return $q;
 }
}

