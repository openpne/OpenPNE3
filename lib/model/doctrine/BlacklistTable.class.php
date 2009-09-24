<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class BlacklistTable extends opDoctrineTable
{
  public function retrieveByUid($mobileUid)
  {
    return $this->createQuery()
      ->where('uid = ?', $mobileUid)
      ->fetchOne();
  }
}
