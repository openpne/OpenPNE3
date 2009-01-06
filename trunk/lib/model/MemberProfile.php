<?php

/**
 * Subclass for representing a row from the 'member_profile' table.
 *
 * 
 *
 * @package lib.model
 */ 
class MemberProfile extends BaseMemberProfileNestedSet
{
  private $name;
  private $caption;

  public function __toString()
  {

    if ($this->getRhtKey() != 2) {
      $string = "";
      $children = $this->getChildren();

      foreach ($children as $child) {
        if ($child->getProfileOptionId()) {
          $option = ProfileOptionPeer::retrieveByPk($child->getProfileOptionId());
          if (!empty($string)) {
            $string .= ", ";
          }
          $string .= $option->getValue();
        }
      }

      return $string;
    }

    if ($this->getProfileOptionId()) {
      $option = ProfileOptionPeer::retrieveByPk($this->getProfileOptionId());
      return (string)$option->getValue();
    }

    return (string)$this->getValue();
  }

  public function getValue()
  {
    if ($this->getRhtKey() != 2) {
      $children = $this->getChildren();
      $value = array();
      foreach ($children as $child) {
        $value[] = $child->getProfileOptionId();
      }
      return $value;
    }
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
    if (is_null($this->caption))
    {
      $this->caption = $this->getProfile()->getCaption();
    }
    return $this->caption;
  }
}
