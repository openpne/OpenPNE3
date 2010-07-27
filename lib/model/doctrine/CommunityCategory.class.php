<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class CommunityCategory extends BaseCommunityCategory
{
  public function __toString()
  {
    return $this->name;
  }

  public function save(Doctrine_Connection $conn = null)
  {
    if ($this->isNew())
    {
      if ($this->getTreeKey())
      {
        $parent = Doctrine::getTable('CommunityCategory')->find($this->getTreeKey());
        if ($parent)
        {
          $this->getNode()->insertAsLastChildOf($parent);
        }
      }
      else
      {
        parent::save($conn);

        $treeObject = Doctrine::getTable('CommunityCategory')->getTree();
        $treeObject->createRoot($this);
      }
    }

    return parent::save($conn);
  }

  public function isRoot()
  {
    return (1 == $this->lft);
  }

  public function deleteAllChildren()
  {
    $children = Doctrine::getTable('CommunityCategory')->retrieveAllChildrenOfCategory($this);
    foreach ($children as $child)
    {
      $child->delete();
    }
  }

  public function getForm()
  {
    return new CommunityCategoryForm($this);
  }
}
