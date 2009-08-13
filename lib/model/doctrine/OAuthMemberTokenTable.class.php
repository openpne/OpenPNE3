<?php
/**
 */
class OAuthMemberTokenTable extends OAuthAbstractTokenTable
{
  public function findByKeyString($key, $type = 'request', $q = null)
  {
    if ($q && 'request' === $type)
    {
      $q = null;
    }

    return parent::findByKeyString($key, $type, $q);
  }
}
