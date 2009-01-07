<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class FilePeer extends BaseFilePeer
{
  public static function retrieveByFilename($filename, PropelPDO $con = null)
  {
    if (is_null($con))
    {
      $con = Propel::getConnection(FilePeer::DATABASE_NAME, Propel::CONNECTION_READ);
    }

    $c = new Criteria(FilePeer::DATABASE_NAME);
    $c->add(FilePeer::NAME, $filename);

    $result = FilePeer::doSelectOne($c, $con);
    return $result;
  }
}
