<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * myUser
 *
 * @package    OpenPNE
 * @subpackage user
 * @author     Kimura Youichi <kim.upsilon@gmail.com>
 */
class myUser extends sfBasicSecurityUser
{
  protected
    $member = null;

  public function getMemberByApiKey($apiKey)
  {
    if (!$apiKey)
    {
      return null;
    }

    $config = Doctrine::getTable('MemberConfig')->createQuery('c')
      ->leftJoin('c.Member')
      ->where('c.name = \'api_key\'')
      ->where('c.value = ?', $apiKey)
      ->fetchOne();

    if (!$config)
    {
      return null;
    }

    return $config->getMember();
  }

  public function getMember()
  {
    if (!is_null($this->member))
    {
      return $this->member;
    }

    $request = sfContext::getInstance()->getRequest();

    $apiKey = $request['apiKey'];
    if (false === $apiKey)
    {
      $exception = new opErrorHttpException('apiKey parameter not specified.');
      throw $exception->setHttpStatusCode(401);
    }

    $member = $this->getMemberByApiKey($apiKey);
    if (is_null($member) || $member->isOnBlackList() || $member->getIsLoginRejected())
    {
      $exception = new opErrorHttpException('Invalid API key.');
      throw $exception->setHttpStatusCode(401);
    }

    $this->member = $member;

    return $member;
  }

  public function getMemberId()
  {
    return $this->getMember()->getId();
  }
}
