<?php

/**
 * Subclass for representing a row from the 'navi' table.
 *
 * 
 *
 * @package lib.model
 */ 
class Navi extends BaseNavi
{
  public function save(PropelPDO $con = null)
  {
    if (!$this->getSortOrder())
    {
      $maxSortOrder = 0;

      $navis = NaviPeer::retrieveByType($this->getType());
      $finalNavi = array_pop($navis);
      if ($finalNavi)
      {
        $maxSortOrder = $finalNavi->getSortOrder();
      }

      $this->setSortOrder($maxSortOrder + 10);
    }

    return parent::save($con);
  }
}
