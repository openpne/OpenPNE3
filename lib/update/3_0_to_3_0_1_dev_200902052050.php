<?php

class opUpdate_3_0_to_3_0_1_dev_200902052050 extends opUpdate
{
  public function update()
  {
    $this->createTable(
      'invitelist',
      array(
        'id' => array('type' => 'integer', 'primary' => true, 'autoincrement' => true),
        'created_at' => array('type' => 'timestamp', 'notnull' => true),
        'member_id_from' => array('type' => 'integer', 'size' => 4, 'foreign' => 'id', 'foreignAlias' => 'member', 'onDelete' => 'CASCADE'),
        'member_id_to' => array('type' => 'integer', 'size' => 4, 'foreign' => 'id', 'foreignAlias' => 'member'),
        'mail_address' => array('type' => 'string', 'size' => 128)
      )
    );
  }
}
