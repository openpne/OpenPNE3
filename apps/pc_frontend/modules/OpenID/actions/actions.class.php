<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * OpenID actions.
 *
 * @package    OpenPNE
 * @subpackage OpenID
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class OpenIDActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    sfOpenPNEApplicationConfiguration::registerJanRainOpenID();
    require_once 'Auth/OpenID/Server.php';
    require_once 'Auth/OpenID/FileStore.php';

    $url = $this->getController()->genUrl('OpenID/index', true);
    $server = new Auth_OpenID_Server(new Auth_OpenID_FileStore(sfConfig::get('sf_cache_dir')), $url);

    $this->getResponse()->setHttpHeader('X-XRDS-Location', $this->getController()->genUrl('OpenID/signonXrds', true));

    $openIDRequest = $server->decodeRequest();
    if (!$openIDRequest)
    {
      $_SESSION['request'] = null;
      return sfView::SUCCESS;
    }

    $_SESSION['request'] = serialize($openIDRequest);
    if (!empty($openIDRequest->mode) && in_array($openIDRequest->mode, array('checkid_immediate', 'checkid_setup')))
    {
      if ($openIDRequest->idSelect())
      {
        if ($openIDRequest->mode === 'checkid_immediate')
        {
          $response = $openIDRequest->answer(false);
        }
        else
        {
          $this->getRequest()->setMethod(sfWebRequest::GET);
          $_SERVER['QUERY_STRING'] = http_build_query($openIDRequest->message->toPostArgs());
          $this->forwardUnless($this->getUser()->isAuthenticated() && $this->getUser()->getMember(), 'member', 'login');

          $trusted = unserialize($this->getUser()->getMember()->getConfig('trusted_openid_rp'));
          if ($trusted && in_array($openIDRequest->trust_root, $trusted))
          {
            $request->setParameter('trust', '1');
            $this->forward('OpenID', 'trust');
          }

          $this->info = $openIDRequest;

          return 'Trust';
        }
      }
      elseif (!$openIDRequest->identity && !$openIDRequest->idSelect())
      {
        $this->forward('@error');
      }
      elseif ($openIDRequest->immediate)
      {
        $response = $openIDRequest->answer(false, $url);
      }
      else
      {
        $this->forwardUnless($this->getUser()->isAuthenticated() && $this->getUser()->getMember(), 'member', 'login');

        $trusted = unserialize($this->getUser()->getMember()->getConfig('trusted_openid_rp'));
        if ($trusted && in_array($openIDRequest->trust_root, $trusted))
        {
          $request->setParameter('trust', '1');
          $this->forward('OpenID', 'trust');
        }

        $this->info = $openIDRequest;
        return 'Trust';
      }
    }
    else
    {
      $response = $server->handleRequest($openIDRequest);
    }

    $response = $server->encodeResponse($response);
    return $this->writeResponse($response);
  }

  public function executeTrust(sfWebRequest $request)
  {
    sfOpenPNEApplicationConfiguration::registerJanRainOpenID();
    require_once 'Auth/OpenID/Server.php';
    require_once 'Auth/OpenID/FileStore.php';
    require_once 'Auth/OpenID/SReg.php';
    require_once 'Auth/OpenID/AX.php';

    $info = unserialize($_SESSION['request']);
    $this->forward404Unless($info);

    $trusted = ($request->hasParameter('trust') || $request->hasParameter('permanent'));
    if (!$trusted)
    {
      unset($_SESSION['request']);
      $url = $info->getCancelURL();
      $this->redirect($url);
    }

    $reqUrl = $this->getController()->genUrl('OpenID/member?id='.$this->getUser()->getMemberId(), true);
    if (!$info->idSelect())
    {
      $this->forward404Unless($reqUrl === $info->identity, 'request:'.$reqUrl.'/identity:'.$info->identity);
    }

    if ($request->hasParameter('permanent'))
    {
      $trusted = unserialize($this->getUser()->getMember()->getConfig('trusted_openid_rp'));
      if (!$trusted)
      {
        $trusted = array();
      }

      $trusted[] = $info->trust_root;

      $this->getUser()->getMember()->setConfig('trusted_openid_rp', serialize($trusted));
    }

    unset($_SESSION['request']);
    $server = new Auth_OpenID_Server(new Auth_OpenID_FileStore(sfConfig::get('sf_cache_dir')), $info->identity);
    $response = $info->answer(true, null, $reqUrl);

    $sregRequest = Auth_OpenID_SRegRequest::fromOpenIDRequest($info);
    if ($sregRequest)
    {
      $sregExchange = new opOpenIDProfileExchange('sreg', $this->getUser()->getMember());
      $sregResp = Auth_OpenID_SRegResponse::extractResponse($sregRequest, $sregExchange->getData());
      $response->addExtension($sregResp);
    }

    $axRequest = Auth_OpenID_AX_FetchRequest::fromOpenIDRequest($info);
    $axResp = new Auth_OpenID_AX_FetchResponse();

    if ($axRequest && !($axRequest instanceof Auth_OpenID_AX_Error))
    {
      $axExchange = new opOpenIDProfileExchange('ax', $this->getUser()->getMember());
      $userData = $axExchange->getData();

      foreach ($axRequest->requested_attributes as $k => $v)
      {
        if (!empty($userData[$k]))
        {
          $axResp->addValue($k, $userData[$k]);
        }
      }

      $response->addExtension($axResp);
    }

    $response = $server->encodeResponse($response);

    return $this->writeResponse($response);
  }

  public function executeMember(sfWebRequest $request)
  {
  }

  public function executeIdpXrds(sfWebRequest $request)
  {
    header('Content-type: application/xrds+xml');
    $type = Auth_OpenID_TYPE_2_0_IDP;
    $uri = $this->getController()->genUrl('OpenID/index', true);
    echo <<<EOF
<?xml version="1.0" encoding="UTF-8"?>
<xrds:XRDS
    xmlns:xrds="xri://\$xrds"
    xmlns="xri://\$xrd*(\$v*2.0)">
  <XRD>
    <Service priority="0">
      <Type>$type</Type>
      <URI>$uri</URI>
    </Service>
  </XRD>
</xrds:XRDS>
EOF;
    return sfView::NONE;
  }

  public function executeSignonXrds(sfWebRequest $request)
  {
    header('Content-type: application/xrds+xml');
    $type = Auth_OpenID_TYPE_2_0;
    $uri = $this->getController()->genUrl('OpenID/index', true);
    echo <<<EOF
<?xml version="1.0" encoding="UTF-8"?>
<xrds:XRDS
    xmlns:xrds="xri://\$xrds"
    xmlns:openid="http://openid.net/xmlns/1.0"
    xmlns="xri://\$xrd*(\$v*2.0)">
  <XRD>
    <Service priority="0">
      <Type>$type</Type>
      <URI>$uri</URI>
    </Service>
  </XRD>
</xrds:XRDS>
EOF;
    return sfView::NONE;
  }

  protected function writeResponse($response)
  {
    foreach ($response->headers as $k => $v)
    {
      $k = str_replace(array("\r", "\n"), '', $k);
      $v = str_replace(array("\r", "\n"), '', $v);

      if ($k && $v)
      {
        header("$k: $v");
      }
    }

    header('Connection: close');

    $body = $response->body;
    if (AUTH_OPENID_HTTP_OK === $response->code)
    {
      $this->form = str_replace('<form ', '<form id="trans" ', $body);
      $this->setTemplate('transfer');

      return sfView::SUCCESS;
    }
    else
    {
      echo $body;
      exit;
    }
  }
}
