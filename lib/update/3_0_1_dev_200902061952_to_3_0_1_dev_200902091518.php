<?php

class opUpdate_3_0_1_dev_200902061952_to_3_0_1_dev_200902091518 extends opUpdate
{
  public function update()
  {
    $this->dropTable('invitelist');
    $this->addColumn('member', 'invite_member_id', 'integer', array('size' => 11));
  }
}
