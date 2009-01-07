<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

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
