<?php

/**
 * Subclass for representing a row from the 'admin_user' table.
 *
 * 
 *
 * @package lib.model
 */ 
class AdminUser extends BaseAdminUser
{
  public function save(PropelPDO $con = null)
  {
    $this->setPassword(md5($this->getPassword()));
    return parent::doSave($con);
  }
}
