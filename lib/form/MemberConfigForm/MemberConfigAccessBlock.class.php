<?php

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
    $blockedId = array();

  public function configure()
  {
    $relations = MemberRelationshipPeer::retrievesByMemberIdFrom($this->member->getId());
    foreach ($relations as $relation)
    {
      if ($relation->getIsAccessBlock())
      {
        $this->blockedId[] = $relation->getMemberIdTo();
      }
    }
  }

  public function saveConfig($name, $value)
  {
    if ($name !== 'access_block')
    {
      return parent::saveConfig($name, $value);
    }

    $memberIds = array_merge($this->blockedId, $value);
    foreach ($memberIds as $memberId)
    {
      $relation = MemberRelationshipPeer::retrieveByFromAndTo($this->member->getId(), $memberId);
      if (!$relation)
      {
        if (!MemberPeer::retrieveByPK($memberId))
        {
          continue;
        }

        $relation = new MemberRelationship();
        $relation->setMemberIdFrom($this->member->getId());
        $relation->setMemberIdTo($memberId);
      }

      $relation->setIsAccessBlock(in_array($memberId, $value));
      $relation->save();
    }
  }

  public function setMemberConfigWidget($name)
  {
    $result = parent::setMemberConfigWidget($name);

    if ($name === 'access_block')
    {
      $this->setDefault($name, $this->blockedId);
    }

    return $result;
  }
}
