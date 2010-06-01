<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class MemberProfile extends BaseMemberProfile implements opAccessControlRecordInterface
{
  public function __toString()
  {
    if ('date' !== $this->getFormType())
    {
      if ($this->getProfileOptionId())
      {
        $option = Doctrine::getTable('ProfileOption')->find($this->getProfileOptionId());
        return (string)$option->getValue();
      }

      $children = $this->getChildrenValues(true);
      if ($children)
      {
        return implode(', ', $children);
      }
    }

    return (string)$this->getValue();
  }

  public function construct()
  {
    if (!$this->isNew())
    {
      $this->mapValue('name', $this->Profile->getName());
      $this->mapValue('caption', $this->Profile->Translation['ja_JP']->caption);
    }

    return parent::construct();
  }

  public function getValue()
  {
    if ($this->_get('value_datetime'))
    {
      $obj = new DateTime($this->_get('value_datetime'));
      return $obj->format('Y-m-d');
    }

    if ($this->getProfile()->isPreset())
    {
      return $this->_get('value');
    }
    elseif ('date' !== $this->getFormType() && $this->getProfileOptionId())
    {
      return $this->getProfileOptionId();
    }

    $children = $this->getChildrenValues();
    if ($children)
    {
      if ('date' === $this->getFormType())
      {
        if (count($children) == 3 && $children[0] && $children[1] && $children[2])
        {
          $obj = new DateTime();
          $obj->setDate($children[0], $children[1], $children[2]);
          return $obj->format('Y-m-d');
        }
        return null;
      }
      return $children;
    }

    return parent::rawGet('value');
  }

  protected function getChildrenValues($isToString = false)
  {
    $values = array();

    if ($this->getNode()->hasChildren())
    {
      $children = $this->getNode()->getChildren();
      foreach ($children as $child)
      {
        if ('date' === $child->getFormType())
        {
          $values[] = $child->getValue();
        }
        elseif ($child->getProfileOptionId())
        {
          if ($isToString)
          {
            $option = Doctrine::getTable('ProfileOption')->find($child->getProfileOptionId());
            $values[] = $option->getValue();
          }
          else
          {
            $values[] = $child->getProfileOptionId();
          }
        }
      }
    }

    return $values;
  }

  public function getFormType()
  {
    return $this->Profile->getFormType();
  }

  public function setValue($value)
  {
    if ($this->getProfile()->isSingleSelect() && !$this->getProfile()->isPreset())
    {
      $this->setProfileOptionId($value);
    }
    else
    {
      $this->_set('value', $value);
    }
  }

  public function preSave($event)
  {
    $modified = $this->getModified();
    if (isset($modified['value_datetime']))
    {
      $this->_set('value', $this->_get('value_datetime'));
    }
    elseif ('date' === $this->getFormType() && isset($modified['value']) && $this->getProfile()->isPreset())
    {
      $this->_set('value_datetime', $this->_get('value'));
    }
  }

  public function postSave($event)
  {
    if ($this->getTreeKey())
    {
      $parent = $this->getTable()->find($this->getTreeKey());
      if ($parent)
      {
        $this->getNode()->insertAsLastChildOf($parent);
      }
    }
    else
    {
      $tree = $this->getTable()->getTree();
      $tree->createRoot($this);
    }
  }

  public function isViewable($memberId = null)
  {
    if (is_null($memberId))
    {
      $memberId = sfContext::getInstance()->getUser()->getMemberId();
    }

    switch ($this->getPublicFlag())
    {
      case ProfileTable::PUBLIC_FLAG_FRIEND:
        $relation = Doctrine::getTable('MemberRelationship')->retrieveByFromAndTo($this->getMemberId(), $memberId);
        if  ($relation && $relation->isFriend())
        {
          return true;
        }

        return ($this->getMemberId() == $memberId);

      case ProfileTable::PUBLIC_FLAG_PRIVATE:
        return false;

      case ProfileTable::PUBLIC_FLAG_SNS:
        return (bool)$memberId;

      case ProfileTable::PUBLIC_FLAG_WEB:
        return ($this->Profile->is_public_web) ? true : (bool)$memberId;
    }
  }

  public function clearChildren()
  {
    if ($this->getTreeKey() && $this->getNode()->hasChildren())
    {
      $children = $this->getNode()->getChildren();
      $children->delete();
    }
  }

  public function generateRoleId(Member $member)
  {
    $relation = Doctrine::getTable('MemberRelationship')->retrieveByFromAndTo($this->Member->id, $member->id);

    if ($this->Member->id === $member->id)
    {
      return 'self';
    }
    elseif ($relation)
    {
      if ($relation->getIsAccessBlock())
      {
        return 'blocked';
      }
      elseif ($relation->getIsFriend())
      {
        return 'friend';
      }
    }

    return 'everyone';
  }
}
