<?php

class CommunityCategoryPeer extends BaseCommunityCategoryNestedSetPeer
{
  static public function retrieveAll()
  {
    $c = new Criteria();
    return parent::doSelect($c);
  }

  static public function retrieveAllRoots(Criteria $c = null)
  {
    if (!$c)
    {
      $c = new Criteria();
    }

    $c->add(self::LEFT_COL, 1, Criteria::EQUAL);
    return parent::doSelect($c);
  }

  static public function retrieveAllChildren(Criteria $c = null)
  {
    if (!$c)
    {
      $c = new Criteria();
    }

    $c->add(self::LEFT_COL, 1, Criteria::GREATER_THAN);
    return parent::doSelect($c);
  }
}
