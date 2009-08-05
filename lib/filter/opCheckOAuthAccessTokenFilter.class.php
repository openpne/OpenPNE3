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

    try
    {
      $req = OAuthRequest::from_request();
      list($consumer, $token) = $this->getServer()->verify_request($req);
    }
    catch (OAuthException $e)
    {
      $filterChain->execute();
    }

    sfContext::getInstance()->getUser()->setAuthenticated(true);

    $filterChain->execute();
  }

  protected function getServer()
  {
    $server = new opOAuthServer(new opOAuthDataStore());

    return $server;
  }
}
