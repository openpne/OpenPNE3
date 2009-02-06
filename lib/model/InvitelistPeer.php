<?php

class InvitelistPeer extends BaseInvitelistPeer
{
  public static function addInvite($id_from, $id_to, $mail_address)
  {
    if (self::getInvite($id_from, $id_to))
    {
      return;
    }
    $invite = new Invitelist();
    $invite->setMemberIdFrom($id_from);
    $invite->setMemberIdTo($id_to);
    $invite->setMailAddress($mail_address);
    $invite->save();
  }

  public static function getInvite($id_from, $id_to)
  {
    $c = new Criteria();
    $c->add(self::MEMBER_ID_FROM, $id_from);
    $c->add(self::MEMBER_ID_TO, $id_to);
    return self::doSelectOne($c);
  }

  public static function getInvitesByMemberIdFrom($id_from)
  {
    $c = new Criteria();
    $c->add(self::MEMBER_ID_FROM, $id_from);
    return self::doSelect($c);
  }

  public static function deleteInvitesByMemberIdTo($id_to)
  {
    $c = new Criteria();
    $c->add(self::MEMBER_ID_TO, $id_to);
    $invites = self::doSelect($c);
    foreach ($invites as $invite)
    {
      $invite->delete();
    }
  }
}
