<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

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
