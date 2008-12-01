<?php
class opCheckPrivilageBelongBehavior
{
  public function checkPrivilageBelong($object, $member_id)
  {
    $this_class = get_class($object);
    $class_name = sfConfig::get('propel_behavior_check_privilage_belong_'.$this_class.'_class_name');

    $class = new $class_name();
    $peer = $class->getPeer();
    $column_name = get_class($peer).'::MEMBER_ID';

    $c = new Criteria();
    $c->add(constant($column_name),  $member_id, Criteria::EQUAL);

    $result = $peer->doSelectStmt($c);
    $row = $result->fetch(PDO::FETCH_NUM);

    if (!$row)
    {
      throw new opPrivilegeException('fail');
    }
  }
}
