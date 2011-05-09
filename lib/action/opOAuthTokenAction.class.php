<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opOAuthTokenAction
 *
 * @package    OpenPNE
 * @subpackage action
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
abstract class opOAuthTokenAction extends sfActions
{
  protected $dataStore = null;

  abstract protected function getTokenModelName();

  protected function getTokenTable()
  {
    return Doctrine::getTable($this->getTokenModelName());
  }

  public function executeRequestToken(sfWebRequest $request)
  {
    require_once 'OAuth.php';

    $authRequest = OAuthRequest::from_request();
    $token = $this->getServer()->fetch_request_token($authRequest);

    $this->tokenRecord = $this->getTokenTable()->findByKeyString($token->key);
    if ($this->tokenRecord)
    {
      $this->tokenRecord->setCallbackUrl($request->getParameter('oauth_callback', 'oob'));
      $this->tokenRecord->setIsActive(false);
      $this->tokenRecord->save();
    }

    $this->getResponse()->setContent((string)$token.'&oauth_callback_confirmed=true');

    return sfView::NONE;
  }

  public function executeAuthorizeToken(sfWebRequest $request)
  {
    $this->token = $request->getParameter('oauth_token');

    $this->information = $this->getTokenTable()->findByKeyString($this->token);
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
    $this->information = $this->getTokenTable()->findByKeyString($requestToken);
    $this->forward404Unless($this->information);
    $this->forward404Unless($this->information->getIsActive());
    $this->forward404Unless($this->information->getVerifier() === $request->getParameter('oauth_verifier'));

    $authRequest = OAuthRequest::from_request();
    $token = $this->getServer()->fetch_access_token($authRequest);

    $this->information->delete();

    $this->getResponse()->setContent((string)$token);

    return sfView::NONE;
  }

  protected function getDataStore()
  {
    if (is_null($this->dataStore))
    {
      $this->dataStore = new opOAuthDataStore();
    }

    return $this->dataStore;
  }

  public function setRecordTemplate(Doctrine_Record $record = null)
  {
    if (!$record)
    {
      $class = $this->getTokenModelName();
      $record = new $class();
    }

    $this->getDataStore()->setRecordTemplate($record);
  }

  public function setQueryTemplate(Doctrine_Query $q = null)
  {
    if (!$q)
    {
      $q = $this->getTokenTable()->createQuery();
    }

    $this->getDataStore()->setQueryTemplate($q);
  }

  protected function getServer()
  {
    $this->getDataStore()->setTokenModelName($this->getTokenModelName());
    $this->setRecordTemplate();
    $this->setQueryTemplate();

    $server = new opOAuthServer($this->getDataStore());

    return $server;
  }
}
