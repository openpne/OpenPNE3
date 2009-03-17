<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class CommunityCategory extends BaseCommunityCategoryNestedSet
{
  public function __toString()
  {
    return $this->getName();
  }

  public function save(PropelPDO $con = null)
  {
    if ($this->isNew())
    {
      if ($this->getTreeKey())
      {
        $parent = CommunityCategoryPeer::retrieveByPk($this->getTreeKey());
        if (!$parent)
        {
          new OutOfBoundsException();
        }

        $this->insertAsLastChildOf($parent);
      }
      else
      {
        $this->makeRoot();
        parent::save($con);
        $this->setTreeKey($this->getId());
      }
    }
    parent::save($con);
  }

  public function getForm()
  {
    return new CommunityCategoryForm($this);
  }
}
