<?php
class opCheckPrivilegeBelongBehavior
{
  public function checkPrivilegeBelong($object, $member_id)
  {
    $this_class = get_class($object);
    $class_name = sfConfig::get('propel_behavior_check_privilege_belong_'.$this_class.'_class_name');

    $class = new $class_name();
    $peer = $class->getPeer();
    $column_name_member_id = get_class($peer).'::MEMBER_ID';
    $column_name_object_id = get_class($peer).'::'.strtoupper($this_class).'_ID';

    $c = new Criteria();
    $c->add(constant($column_name_member_id), $member_id, Criteria::EQUAL);
    $c->add(constant($column_name_object_id), $object->getId(),Criteria::EQUAL);

    $result = $peer->doSelectStmt($c);
    $row = $result->fetch(PDO::FETCH_NUM);

    if (!$row)
    {
      throw new opPrivilegeException('fail');
    }
  }

  public function isPrivilegeBelong($object, $member_id)
  {
    try {
      $this->checkPrivilegeBelong($object, $member_id);
      return true;
    } catch (opPrivilegeException $e) {
      return false;
    }
  }
}
