<?php

class IntroEssayPeer extends BaseIntroEssayPeer
{
  /**
   * Get introEssay from from_id and to_id
   * @param int $from_id member id
   * @param int $to_id member id
   */
  public function getByFromAndTo($from_id, $to_id)
  {
    $criteria = new Criteria();
    $criteria->add(self::FROM_ID, $from_id);
    $criteria->add(self::TO_ID, $to_id);
    return self::doSelectOne($criteria);
  }
}
