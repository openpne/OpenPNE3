<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * MemberConfigAccessBlockForm form.
 *
 * @package    OpenPNE
 * @subpackage form
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class MemberConfigAccessBlockForm extends MemberConfigForm
{
  protected
    $category = 'accessBlock',
    $blockedId = array(),
    $setBlockedId = array();

  public function configure()
  {
    $this->widgetSchema->setHelp('access_block',
      'Block access from the selected member with input MemberID.<br />'
    . ' MemberID is written at the end of member top page URL.<br />'
    . ' ex. The MemberID is 1 when the URL "http://sns.example.com/member/1"');
    $relations = Doctrine::getTable('MemberRelationship')->retrievesAccessBlockByMemberIdFrom($this->member->getId());
    foreach ($relations as $relation)
    {
      $this->blockedId[] = $relation['member_id_to'];
      $this->blockedRelationshipId[] = $relation['id'];
    }
  }

  protected function appendMobileInputMode()
  {
    parent::appendMobileInputMode();

    foreach ($this as $k => $v)
    {
      $widget = $this->widgetSchema[$k];
      $validator = $this->validatorSchema[$k];

      if ($widget instanceof opWidgetFormInputIncreased)
      {
        opToolkit::appendMobileInputModeAttributesForFormWidget($widget, 'numeric');
      }
    }
  }

  public function saveConfig($name, $value)
  {
    if ('access_block' !== $name)
    {
      return parent::saveConfig($name, $value);
    }
    $value = $this->setBlockedIds;
    $key = 0;
    foreach ($value as $memberId)
    {
      $defaultId = 0;
      if ($key + 1 <= count($this->blockedId))
      {
        $defaultId = $this->blockedId[$key];
      }

      switch ($memberId)
      {
      case '':
        // delete
        if (!$defaultId)
        {
          break;
        }
        $relationship = Doctrine::getTable('MemberRelationship')
          ->retrieveByFromAndTo($this->member->getId(), $defaultId);
        if (!$relationship)
        {
          break;
        }
        $relationship->setIsAccessBlock(false);
        $relationship->save();
        break;
      case $defaultId:
        // equal
        break;
      default:
        $relationship = Doctrine::getTable('MemberRelationship')
          ->retrieveByFromAndTo($this->member->getId(), $memberId);
        // update
        if ($defaultId)
        {
          if (!$relationship)
          {
            $relationship = Doctrine::getTable('MemberRelationship')
              ->retrieveByFromAndTo($this->member->getId(), $defaultId);
          }
        }
        // insert
        else
        {
          if (!$relationship)
          {
            $relationship = new MemberRelationship();
            $relationship->setMemberIdFrom($this->member->getId());
          }
        }
        $relationship->setMemberIdTo($memberId);
        $relationship->setIsAccessBlock(true);
        $relationship->save();
      }
      if ($key >= count($this->blockedId))
      {
        break;
      }
      $key++;
    }
  }

  public function bind($params)
  {
    $this->setBlockedIds = $params['access_block'];

    return parent::bind($params);
  }

  public function setMemberConfigWidget($name)
  {
    $result = parent::setMemberConfigWidget($name);

    if ('access_block' === $name)
    {
      $this->setDefault($name, $this->blockedId);

      $this->mergePostValidator(new sfValidatorCallback(array(
        'callback'  => array('MemberConfigAccessBlockForm', 'validate'),
        'arguments' => array('ids' => $this->blockedId),
      )));
    }

    return $result;
  }

  public static function validate($validator, $values, $arguments = array())
  {
    $result = array();

    $memberIds = array_merge($arguments['ids'], $values['access_block']);

    if (in_array(sfContext::getInstance()->getUser()->getMemberId(), $memberIds))
    {
      throw new sfValidatorError($validator, 'invalid');
    }

    foreach ($memberIds as $memberId)
    {
      if (!$memberId)
      {
        continue;
      }

      if (!Doctrine::getTable('Member')->find($memberId))
      {
        throw new sfValidatorError($validator, 'invalid');
      }

      $result[] = $memberId;
    }

    $values['access_block'] = $result;

    return $values;
  }
}
