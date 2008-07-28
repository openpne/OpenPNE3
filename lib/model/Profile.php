<?php

/**
 * Subclass for representing a row from the 'profile' table.
 *
 * 
 *
 * @package lib.model
 */ 
class Profile extends BaseProfile
{
  public function hydrate(ResultSet $rs, $startcol = 1)
  {
    parent::hydrate($rs, $startcol);
    $this->setCulture(sfContext::getInstance()->getUser()->getCulture());
  }
}
