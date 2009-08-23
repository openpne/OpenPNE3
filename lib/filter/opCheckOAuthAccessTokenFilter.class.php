<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opCheckOAuthAccessToken
 *
 * @package    OpenPNE
 * @subpackage filter
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opCheckOAuthAccessTokenFilter extends sfFilter
{
  public function execute($filterChain)
  {
    require_once 'OAuth.php';
    $consumer = $token = null;

    try
    {
      $req = OAuthRequest::from_request();
      list($consumer, $token) = $this->getServer()->verify_request($req);
    }
    catch (OAuthException $e)
    {
      // do nothing
    }

    if ($consumer)
    {
      sfContext::getInstance()->getUser()->setAuthenticated(true);
      $information = Doctrine::getTable('OAuthConsumerInformation')->findByKeyString($consumer->key);
      if ($information)
      {
        sfContext::getInstance()->getUser()->addCredentials($information->getUsingApis());
      }

      $tokenType = $this->context->getRequest()->getParameter('token_type', 'member');
      if ('member' === $tokenType)
      {
        $accessToken = Doctrine::getTable('OAuthMemberToken')->findByKeyString($token->key, 'access');
        sfContext::getInstance()->getUser()->setAttribute('member_id', $accessToken->getMember()->id);
      }
    }

    $route = $this->context->getRequest()->getAttribute('sf_route');
    if ($route instanceof opAPIRouteInterface)
    {
      $actionInstance = $this->context->getController()->getActionStack()->getLastEntry()->getActionInstance();

      $config = $actionInstance->getSecurityConfiguration();
      if (!isset($config['all']['credentials']))
      {
        $config['all']['credentials'] = array();
      }

      $config['all']['credentials'] = array_merge($config['all']['credentials'], array($route->getAPIName()));

      $actionInstance->setSecurityConfiguration($config);
    }

    $filterChain->execute();
  }

  protected function getServer()
  {
    $tokenType = $this->context->getRequest()->getParameter('token_type', 'member');
    $dataStore = new opOAuthDataStore();

    $dataStore->setTokenModelName('OAuth'.ucfirst($tokenType).'Token');
    $server = new opOAuthServer($dataStore);

    return $server;
  }
}
