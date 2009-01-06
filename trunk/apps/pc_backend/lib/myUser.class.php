<?php

class myUser extends sfBasicSecurityUser
{
  public function getId()
  {
    return $this->getAttribute('adminUserId', null, 'adminUser');
  }
}
