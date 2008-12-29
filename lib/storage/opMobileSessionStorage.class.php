<?php

class opMobileSessionStorage extends sfSessionStorage
{
  public function initialize($options = null)
  {
    if (!sfContext::getInstance()->getRequest()->isCookie())
    {
      ini_set('use_only_cookies', 0);
      ini_set('use_cookies', 0);
      ini_set('session.use_trans_sid', 1);
    }

    parent::initialize($options);
  }
}
