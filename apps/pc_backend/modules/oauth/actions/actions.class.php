<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * oauth actions.
 *
 * @package    OpenPNE
 * @subpackage action
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class oauthActions extends sfActions
{
  public function executeRequestToken(sfWebRequest $request)
  {
    require_once 'OAuth.php';

    $authRequest = OAuthRequest::from_request();
    $token = $this->getServer()->fetch_request_token($authRequest);

    $adminToken = Doctrine::getTable('OAuthAdminToken')->findByKeyString($token->key);
    if ($adminToken)
    {
      $adminToken->setCallbackUrl($request->getParameter('oauth_callback', 'oob'));
      $adminToken->setIsActive(false);
      $adminToken->save();
    }

    $this->getResponse()->setContent((string)$token.'&oauth_callback_confirmed=true');

    return sfView::NONE;
  }

  public function executeAuthorizeToken(sfWebRequest $request)
  {
    $this->token = $request->getParameter('oauth_token');

    $this->information = Doctrine::getTable('OAuthAdminToken')->findByKeyString($this->token);
    $this->forward404Unless($this->information);

    if ($request->isMethod(sfWebRequest::POST))
    {
      $url = $this->information->getCallbackUrl();
      $params = array('oauth_token' => $this->token, 'oauth_verifier' => $this->information->getVerifier());
      $query = (false === strpos($url, '?') ? '?' : '&' ).OAuthUtil::build_http_query($params);

      $this->information->setIsActive(true);
      $this->information->save();

      $this->redirectUnless('oob' === $url, $url.$query);

      return sfView::SUCCESS;
    }

    return sfView::INPUT;
  }

  public function executeAccessToken(sfWebRequest $request)
  {
    require_once 'OAuth.php';

    $requestToken = $request->getParameter('oauth_token');
    $this->information = Doctrine::getTable('OAuthAdminToken')->findByKeyString($requestToken);
    $this->forward404Unless($this->information);
    $this->forward404Unless($this->information->getIsActive());
    $this->forward404Unless($this->information->getVerifier() === $request->getParameter('oauth_verifier'));

    $authRequest = OAuthRequest::from_request();
    $token = $this->getServer()->fetch_access_token($authRequest);

    $this->getResponse()->setContent((string)$token);

    return sfView::NONE;
  }

  protected function getServer()
  {
    $server = new opOAuthServer(new opOAuthDataStore());

    return $server;
  }
}
