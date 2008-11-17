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
  private $name;
  private $caption;

  public function __toString()
  {
    if ($this->getProfileOptionId()) {
      $option = ProfileOptionPeer::retrieveByPk($this->getProfileOptionId());
      return $option->getValue();
    }

    return $this->getValue();
  }

  public function getValue()
  {
    if ($this->getProfileOptionId()) {
      return $this->getProfileOptionId();
    }

    return parent::getValue();
  }

  public function hydrateProfiles($row)
  {
    try {
      $col = parent::hydrate($row);
      $this->name = $row[$col+0];
      $this->caption = $row[$col+1];
    } catch (Exception $e) {
      throw new PropelException("Error populating MemberProfile object", $e);
    }
  }

  public function getName()
  {
    return $this->name;
  }

  public function getCaption()
  {
    return $this->caption;
  }
}
