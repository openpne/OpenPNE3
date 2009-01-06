<?php
class opCheckPrivilegeOwnerBehavior
{
  public function checkPrivilegeOwner($object, $memberId)
  {
    $thisClass = get_class($object);
    $className = sfConfig::get('propel_behavior_check_privilege_owner_'.$thisClass.'_class_name');

    $class = new $className();
    $peer = $class->getPeer();
    $columnNameMemberId = get_class($peer).'::MEMBER_ID';
    $columnNameObjectId = get_class($peer).'::'.strtoupper($thisClass).'_ID';
    $columnNamePosition = get_class($peer).'::POSITION';

    $c = new Criteria();
    $c->add(constant($columnNameMemberId), $memberId, Criteria::EQUAL);
    $c->add(constant($columnNameObjectId), $object->getId(),Criteria::EQUAL);
    $c->add(constant($columnNamePosition), 'admin', Criteria::EQUAL);

    $result = $peer->doSelectStmt($c);
    $row = $result->fetch(PDO::FETCH_NUM);

    if (!$row)
    {
      throw new opPrivilegeException('fail');
    }
  }

  public function isPrivilegeOwner($object, $memberId)
  {
    try {
      $this->checkPrivilegeOwner($object, $memberId);
      return true;
    } catch (opPrivilegeException $e) {
      return false;
    }
  }
}
