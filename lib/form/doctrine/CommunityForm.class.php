<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * Community form.
 *
 * @package    OpenPNE
 * @subpackage form
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class CommunityForm extends BaseCommunityForm
{
  protected $configForm;

  public function configure()
  {
    unset($this['created_at'], $this['updated_at'], $this['file_id']);
    unset($this->widgetSchema['id']);

    $this->widgetSchema->setLabel('name', '%community% Name');
    $this->setValidator('name', new opValidatorString(array('max_length' => 64, 'trim' => true)));

    $isAllowMemberCommunity = 1 != sfContext::getInstance()->getUser()->getMemberId();
    $communityCategories = Doctrine::getTable('CommunityCategory')->getAllChildren($isAllowMemberCommunity);
    if (0 < count($communityCategories))
    {
      $choices = array();
      foreach ($communityCategories as $category)
      {
        $choices[$category->id] = $category->name;
      }
      $currentCategoryId = $this->object->community_category_id;
      if (!is_null($currentCategoryId) && !isset($choices[$currentCategoryId]))
      {
        $choices[$currentCategoryId] = $this->object->CommunityCategory->name;
      }
      $this->setWidget('community_category_id', new sfWidgetFormChoice(array('choices' => array('' => '') + $choices)));
      $this->widgetSchema->setLabel('community_category_id', '%community% Category');
    }
    else
    {
      unset($this['community_category_id']);
    }

    $this->widgetSchema->getFormFormatter()->setTranslationCatalogue('form_community');

    $uniqueValidator = new sfValidatorDoctrineUnique(array('model' => 'Community', 'column' => array('name')));
    $uniqueValidator->setMessage('invalid', 'An object with the same "name" already exist in other %community%.');
    $this->validatorSchema->setPostValidator($uniqueValidator);

    $this->mergePostValidator(new sfValidatorCallback(array('callback' => array($this, 'checkCreatable'))));
  }

  public function updateObject($values = null)
  {
    $object = parent::updateObject($values);

    $this->saveMember($object);

    return $object;
  }

  public function saveMember(Community $community)
  {
    if ($this->isNew())
    {
      $member = new CommunityMember();
      $member->setMemberId(sfContext::getInstance()->getUser()->getMemberId());
      $member->setCommunity($community);
      $member->addPosition('admin');
      $member->save();
    }
  }

  public function checkCreatable($validator, $value)
  {
    if (empty($value['community_category_id']))
    {
      return $value;
    }

    $category = Doctrine::getTable('CommunityCategory')->find($value['community_category_id']);
    if (!$category)
    {
      throw new sfValidatorError($validator, 'invalid');
    }

    if ($category->getIsAllowMemberCommunity())
    {
      return $value;
    }

    if (1 == sfContext::getInstance()->getUser()->getMemberId())
    {
      return $value;
    }

    if ($this->object->community_category_id === $category->id)
    {
      return $value;
    }

    throw new sfValidatorError($validator, 'invalid');
  }
}
