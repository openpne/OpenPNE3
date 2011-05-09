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
class oauthActions extends opOAuthTokenAction
{
  protected function getTokenModelName()
  {
    return 'OAuthMemberToken';
  }

  public function setRecordTemplate(Doctrine_Record $record = null)
  {
    $class = $this->getTokenModelName();
    $record = new $class();

    $record->setMemberId($this->getUser()->getMemberId());

    parent::setRecordTemplate($record);
  }

  public function setQueryTemplate(Doctrine_Query $q = null)
  {
    $q = $this->getTokenTable()->createQuery()
      ->andWhere('member_id = ?', $this->getUser()->getMemberId());

    parent::setQueryTemplate($q);
  }

  public function executeAuthorizeToken(sfWebRequest $request)
  {
    $authRequest = OAuthRequest::from_request();
    $this->token = $authRequest->get_parameter('oauth_token');

    $this->information = $this->getTokenTable()->findByKeyString($this->token);
    $this->forward404Unless($this->information);

    if ($request->isMethod(sfWebRequest::POST))
    {
      if (!$request->getParameter('allow'))
      {
        $this->information->delete();

        return 'Delete';
      }

      $url = $this->information->getCallbackUrl();
      $params = array('oauth_token' => $this->token, 'oauth_verifier' => $this->information->getVerifier());
      $query = (false === strpos($url, '?') ? '?' : '&' ).OAuthUtil::build_http_query($params);

      $this->information->setIsActive(true);
      $this->information->setMemberId($this->getUser()->getMemberId());
      $this->information->save();

      $this->redirectUnless('oob' === $url, $url.$query);

      return sfView::SUCCESS;
    }

    return sfView::INPUT;
  }

  public function executeAccessToken(sfWebRequest $request)
  {
    require_once 'OAuth.php';

    $authRequest = OAuthRequest::from_request();
    $requestToken = $authRequest->get_parameter('oauth_token');
    $this->information = $this->getTokenTable()->findByKeyString($requestToken);
    $this->forward404Unless($this->information);
    $this->forward404Unless($this->information->getIsActive());
    $this->forward404Unless($this->information->getVerifier() === $authRequest->get_parameter('oauth_verifier'));

    $this->getUser()->setMemberId($this->information->getMemberId());

    $token = $this->getServer()->fetch_access_token($authRequest);

    $this->getResponse()->setContent((string)$token);

    return sfView::NONE;
  }
}
