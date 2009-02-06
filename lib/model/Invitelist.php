<?php

class Invitelist extends BaseInvitelist
{
  public function deleteRelation(PropelPDO $con = null)
  {
    $relation = MemberRelationshipPeer::retrieveByFromAndTo(
      $this->getMemberIdFrom(), $this->getMemberIdTo());

    if ($relation)
    {
      $relation->delete();
    }

    $relation = MemberRelationshipPeer::retrieveByFromAndTo(
      $this->getMemberIdTo(), $this->getMemberIdFrom());

    if ($relation)
    {
      $relation->delete();
    }
  }
}
