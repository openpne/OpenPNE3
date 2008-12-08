<?php
class opActivateBehavior
{
  static protected $enabled = true;

  public function doSelectStmt($class, Criteria $criteria, $con = null)
  {
    if (self::$enabled)
    {
      $criteria->add(call_user_func(array($class, 'translateFieldName'), 'is_active', BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_COLNAME), 0, Criteria::NOT_EQUAL);
    }
    else
    {
      self::enable();
    }
  }

  public function doCount($class, Criteria $criteria, $con = null)
  {
    if (self::$enabled)
    {
      $criteria->add(call_user_func(array($class, 'translateFieldName'), 'is_active', BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_COLNAME), 0, Criteria::NOT_EQUAL);
    }
    else
    {
      self::enable();
    }
  }

  public static function enable()
  {
    self::$enabled = true;
  }

  public static function disable()
  {
    self::$enabled = false;
  }
}
