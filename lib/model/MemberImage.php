<?php

class MemberImage extends BaseMemberImage
{
  public function __toString()
  {
    return $this->getFile()->getName();
  }

  public function getIsPrimary()
  {
    if (parent::getIsPrimary())
    {
      return true;
    }

    $primaryImage = $this->getMember()->getImage();
    if ($primaryImage)
    {
      return (bool)($primaryImage->getId() == $this->getId());
    }

    return false;
  }
}
