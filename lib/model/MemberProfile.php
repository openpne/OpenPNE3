<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class MemberProfile extends BaseMemberProfileNestedSet
{
  protected
    $name,
    $caption;

  public function __toString()
  {
    if ('date' !== $this->getFormType())
    {
      if ($this->getProfileOptionId())
      {
        $option = ProfileOptionPeer::retrieveByPk($this->getProfileOptionId());
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

  public function getValue()
  {
    if ('date' !== $this->getFormType() && $this->getProfileOptionId())
    {
      return $this->getProfileOptionId();
    }

    $children = $this->getChildrenValues();
    if ($children)
    {
      if ('date' === $this->getFormType())
      {
        $obj = new DateTime();
        $obj->setDate($children[0], $children[1], $children[2]);
        return $obj->format('Y-m-d');
      }
      return $children;
    }

    return parent::getValue();
  }

  protected function getChildrenValues($isToString = false)
  {
    $values = array();

    if ($this->hasChildren())
    {
      $children = $this->getChildren();
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
            $option = ProfileOptionPeer::retrieveByPk($child->getProfileOptionId());
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

  public function clearChildren()
  {
    $c = new Criteria();
    $c->add(MemberProfilePeer::TREE_KEY, $this->getTreeKey());
    MemberProfilePeer::doDelete($c);
  }

  public function getFormType()
  {
    return $this->getProfile()->getFormType();
  }

  public function hydrateProfiles($row)
  {
    try
    {
      $col = parent::hydrate($row);
      $this->name = $row[$col+0];
      $this->caption = $row[$col+1];
    }
    catch (Exception $e)
    {
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

  public function setValue($v)
  {
    if ($this->getProfile()->isSingleSelect())
    {
      return $this->setProfileOptionId((int)$v);
    }

    return parent::setValue($v);
  }
}
