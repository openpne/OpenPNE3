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

      $manager = new OAuthTokenManager();
      $manager->setKeyString($key);
      $manager->setSecret($secret);
      $manager->setConsumer($information);
      $manager->save();

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
      $secret = opToolkit::generatePasswordString(32);

      $manager = new OAuthTokenManager();
      $manager->setKeyString($key);
      $manager->setSecret($secret);
      $manager->setConsumer($information);
      $manager->setType('access');
      $manager->save();

      return new OAuthToken($key, $secret);
    }

    return null;
  }

  public function lookup_token($consumer, $token_type, $token)
  {
    $manager = Doctrine::getTable('OAuthTokenManager')->findByKeyString($token, $token_type);
    if ($manager)
    {
      return new OAuthToken($manager->getKeyString(), $manager->getSecret());
    }

    return null;
  }
}
