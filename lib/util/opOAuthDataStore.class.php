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
  protected
    $tokenModelName = null,

    $queryTemplate = null,
    $recordTemplate = null;

  public function setTokenModelName($name)
  {
    $this->tokenModelName = $name;
  }

  public function getTokenModelName()
  {
    return $this->tokenModelName;
  }

  protected function getTokenTable()
  {
    return Doctrine::getTable($this->getTokenModelName());
  }

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

      $tokenRecord = $this->recordTemplate;
      $tokenRecord->setKeyString($key);
      $tokenRecord->setSecret($secret);
      $tokenRecord->setConsumer($information);
      $tokenRecord->setVerifier($verifier);
      $tokenRecord->save();

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

      $tokenRecord = $this->queryTemplate
        ->andWhere('oauth_consumer_id = ?', $information->id)
        ->andWhere('type = ?', 'access')
        ->fetchOne();

      if (!$tokenRecord)
      {
        $tokenRecord = $this->recordTemplate;
      }
      $tokenRecord->setKeyString($key);
      $tokenRecord->setSecret($secret);
      $tokenRecord->setConsumer($information);
      $tokenRecord->setType('access');
      $tokenRecord->save();

      return new OAuthToken($key, $secret);
    }

    return null;
  }

  public function lookup_token($consumer, $token_type, $token)
  {
    $tokenRecord = $this->getTokenTable()->findByKeyString($token, $token_type, $this->queryTemplate);
    if ($tokenRecord)
    {
      $token = new OAuthToken($tokenRecord->getKeyString(), '');
      if ('request' !== $token_type)
      {
        $token->secret = $tokenRecord->getSecret();
      }
      return $token;
    }

    return null;
  }

  public function setRecordTemplate(Doctrine_Record $record)
  {
    $this->recordTemplate = $record;
  }

  public function setQueryTemplate(Doctrine_Query $q)
  {
    $this->queryTemplate = $q;
  }
}
