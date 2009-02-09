<?php

class opUpdate_3_0_1_dev_200902091518_to_3_0_1_dev_200902091556 extends opUpdate
{
  public function update()
  {
    $this->createForeignKey('member', array(
      'name'         => 'member_FI_1',
      'local'        => 'invite_member_id',
      'foreign'      => 'id',
      'foreignTable' => 'member',
      'onDelete'     => 'SET NULL'
    ));
  }
}
