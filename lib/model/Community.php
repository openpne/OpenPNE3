<?php

/**
 * Subclass for representing a row from the 'community' table.
 *
 * 
 *
 * @package lib.model
 */ 
class Community extends BaseCommunity
{
  public function getImageFileName()
  {
    if ($this->getFile())
    {
      return $this->getFile()->getName();
    }
    return '';
  }
}
