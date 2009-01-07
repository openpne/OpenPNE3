<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class MemberImage extends BaseMemberImage
{
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
