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
  public function configure()
  {
    $this->disableCSRFProtection();

    $widgets = array('name' => new sfWidgetFormInput());
    $validators = array('name' => new sfValidatorPass());

    foreach ($this->getProfiles() as $profile)
    {
      $widgets[$profile->getName()] = opFormItemGenerator::generateSearchWidget($profile->toArray(), $profile->getOptionsArray());
      $validators[$profile->getName()] = new sfValidatorPass();
    }

    $this->setWidgets($widgets);
    $this->setValidators($validators);

    $this->widgetSchema->setLabel('name', 'Nickname');

    $this->widgetSchema->setNameFormat('member[%s]');
  }

  public function getQuery()
  {
    $isWhere = false;
    $ids = null;
    if ($this->getValue('name'))
    {
      $ids = Doctrine::getTable('Member')->searchMemberIds($this->getValue('name'));
      if (!$ids)
      {
        $ids = array();
      }
      $isWhere = true;
    }

    $profileValues = array();
    foreach ($this->getProfiles() as $profile)
    {
      $key = $profile->getName();
      $value = $this->getValue($key);
      if (!empty($value))
      {
        $profileValues[$key] = $value;
        $isWhere = true;
      }
    }

    $ids = Doctrine::getTable('MemberProfile')->searchMemberIds($profileValues, $ids);

    $q = Doctrine::getTable('Member')->createQuery();

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

  protected function getProfiles()
  {
    return Doctrine::getTable('Profile')->retrievesAll();
  }
}

