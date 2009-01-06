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
  public function doSave($con = null)
  {
    $this->setPassword(md5($this->getPassword()));
    return parent::doSave($con);
  }
}
