<?php

class Invitelist extends BaseInvitelist
{
  public function delete(PropelPDO $con = null)
  {
    $relation = MemberRelationshipPeer::retrieveByFromAndTo(
      $this->getMemberIdFrom(), $this->getMemberIdTo());

    if ($relation)
    {
      $relation->delete();
    }

    parent::delete($con);
  }
}
