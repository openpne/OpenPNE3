<?php

require_once 'OAuth.php';

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opOAuthDataStore
 *
 * @package    OpenPNE
 * @subpackage util
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opOAuthDataStore extends OAuthDataStore
{
  public function lookup_consumer($consumer_key)
  {
    $information = Doctrine::getTable('OAuthConsumerInformation')->findByKeyString($consumer_key);
    if ($information)
    {
      return new OAuthConsumer($information['key_string'], $information['secret']);
    }

    return null;
  }

  public function new_request_token($consumer)
  {
    $information = Doctrine::getTable('OAuthConsumerInformation')->findByKeyString($consumer->key);
    if ($information)
    {
      $key = opToolkit::generatePasswordString(16, false);
      $secret = opToolkit::generatePasswordString(32, false);
      $verifier = opToolkit::generatePasswordString(8, false);

      $adminToken = Doctrine::getTable('OAuthAdminToken')
        ->findOneByOauthConsumerIdAndType($information->id, 'request');
      if (!$adminToken)
      {
        $adminToken = new OAuthAdminToken();
      }

      $adminToken->setKeyString($key);
      $adminToken->setSecret($secret);
      $adminToken->setConsumer($information);
      $adminToken->setVerifier($verifier);
      $adminToken->save();

      return new OAuthToken($key, $secret);
    }

    return null;
  }

  public function new_access_token($token, $consumer)
  {
    $information = Doctrine::getTable('OAuthConsumerInformation')->findByKeyString($consumer->key);
    if ($information)
    {
      $key = opToolkit::generatePasswordString(16, false);
      $secret = opToolkit::generatePasswordString(32, false);

      $adminToken = Doctrine::getTable('OAuthAdminToken')
        ->findOneByOauthConsumerIdAndType($information->id, 'access');
      if (!$adminToken)
      {
        $adminToken = new OAuthAdminToken();
      }
      $adminToken->setKeyString($key);
      $adminToken->setSecret($secret);
      $adminToken->setConsumer($information);
      $adminToken->setType('access');
      $adminToken->save();

      return new OAuthToken($key, $secret);
    }

    return null;
  }

  public function lookup_token($consumer, $token_type, $token)
  {
    $adminToken = Doctrine::getTable('OAuthAdminToken')->findByKeyString($token, $token_type);
    if ($adminToken)
    {
      $token = new OAuthToken($adminToken->getKeyString(), '');
      if ('request' !== $token_type)
      {
        $token->secret = $adminToken->getSecret();
      }
      return $token;
    }

    return null;
  }
}
