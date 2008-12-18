<?php
class opCheckPrivilegeBelongBehavior
{
  public function checkPrivilegeBelong($object, $memberId)
  {
    $thisClass = get_class($object);
    $className = sfConfig::get('propel_behavior_check_privilege_belong_'.$thisClass.'_class_name');

    $class = new $className();
    $peer = $class->getPeer();
    $columnNameMemberId = get_class($peer).'::MEMBER_ID';
    $columnNameObjectId = get_class($peer).'::'.strtoupper($thisClass).'_ID';

    $c = new Criteria();
    $c->add(constant($columnNameMemberId), $memberId, Criteria::EQUAL);
    $c->add(constant($columnNameObjectId), $object->getId(),Criteria::EQUAL);

    $result = $peer->doSelectStmt($c);
    $row = $result->fetch(PDO::FETCH_NUM);

    if (!$row)
    {
      throw new opPrivilegeException('fail');
    }
  }

  public function isPrivilegeBelong($object, $memberId)
  {
    try {
      $this->checkPrivilegeBelong($object, $memberId);
      return true;
    } catch (opPrivilegeException $e) {
      return false;
    }
  }
}
