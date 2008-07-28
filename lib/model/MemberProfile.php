<?php

/**
 * Subclass for representing a row from the 'member_profile' table.
 *
 * 
 *
 * @package lib.model
 */ 
class MemberProfile extends BaseMemberProfile
{
  function __toString()
  {
    return $this->getValue();
  }
}
