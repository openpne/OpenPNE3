<?php

class MemberImage extends BaseMemberImage
{
  public function __toString()
  {
    return (string)$this->getFile();
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
