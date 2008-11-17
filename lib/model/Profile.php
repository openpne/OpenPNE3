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
	public function hydrate($row, $startcol = 0, $rehydrate = false)
  {
    $this->setCulture(sfContext::getInstance()->getUser()->getCulture());
    return parent::hydrate($row, $startcol, $rehydrate);
  }
}
