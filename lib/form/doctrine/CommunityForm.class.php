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

    $this->setValidator('name', new sfValidatorString(array('max_length' => 64, 'trim' => true)));

    $q = Doctrine::getTable('CommunityCategory')->createQuery()->where('lft > 1');
    if (1 != sfContext::getInstance()->getUser()->getMemberId())
    {
      $q->andWhere('is_allow_member_community = 1');
    }
    $this->setWidget('community_category_id', new sfWidgetFormDoctrineChoice(array(
      'model'       => 'CommunityCategory',
      'add_empty'   => false,
      'query'    => $q,
    )));
    $this->widgetSchema->setLabel('community_category_id', 'Community Category');
    $this->widgetSchema->getFormFormatter()->setTranslationCatalogue('form_community');

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
      $member->setPosition('admin');
      $member->setMemberId(sfContext::getInstance()->getUser()->getMemberId());
      $member->setCommunity($community);
      $member->save();
    }
  }

  public function checkCreatable($validator, $value)
  {
    $category = Doctrine::getTable('CommunityCategory')->find($value['community_category_id']);
    if ($category->getIsAllowMemberCommunity())
    {
      return $value;
    }

    if (1 == sfContext::getInstance()->getUser()->getMemberId())
    {
      return $value;
    }

    throw new sfValidatorError($validator, 'invalid');
  }
}
