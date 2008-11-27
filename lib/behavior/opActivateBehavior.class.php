<?php
class opActivateBehavior
{
  public function doSelectStmt($class, Criteria $criteria, $con = null)
  {
    $criteria->add(call_user_func(array($class, 'translateFieldName'), 'is_active', BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_COLNAME), 0, Criteria::NOT_EQUAL);
  }

  public function doCount($class, Criteria $criteria, $con = null)
  {
    $criteria->add(call_user_func(array($class, 'translateFieldName'), 'is_active', BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_COLNAME), 0, Criteria::NOT_EQUAL);
  }
}
