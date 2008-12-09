<?php

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
