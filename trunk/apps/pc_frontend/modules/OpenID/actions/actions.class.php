<?php

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

//    header('X-XRDS-Location: '.$this->getController()->genUrl('OpenID/idpXrds'));

    $openIDRequest = $server->decodeRequest();
    if (!$openIDRequest)
    {
      $_SESSION['request'] = serialize($openIDRequest);
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

        $this->info = $openIDRequest;
        return 'Trust';
      }
    }
    else
    {
      $response = $server->handleRequest($openIDRequest);
    }

    $response = $server->encodeResponse($response);
    $this->writeResponse($response);
  }

  public function executeTrust(sfWebRequest $request)
  {
    sfOpenPNEApplicationConfiguration::registerJanRainOpenID();
    require_once 'Auth/OpenID/Server.php';
    require_once 'Auth/OpenID/FileStore.php';

    $info = unserialize($_SESSION['request']);
    $this->forward404Unless($info);

    $trusted = $request->hasParameter('trust');
    if (!$trusted)
    {
      unset($_SESSION['request']);
      $url = $info->getCancelURL();
      $this->redirect($url);
    }

    $reqUrl = $this->getController()->genUrl('OpenID/member?id='.$this->getUser()->getMemberId(), true);
    $this->forward404Unless($reqUrl === $info->identity, 'request:'.$reqUrl.'/identity:'.$info->identity);

    if ($trusted)
    {
      unset($_SESSION['request']);
      $server = new Auth_OpenID_Server(new Auth_OpenID_FileStore(sfConfig::get('sf_cache_dir')), $info->identity);
      $response = $server->encodeResponse($info->answer(true, null, $reqUrl));
      $this->writeResponse($response);
    }

    $this->forward404();
  }

  public function executeMember(sfWebRequest $request)
  {
  }

  public function executeIdpXrds(sfWebRequest $request)
  {
    header('Content-type: application/xrds+xml');
    $type = Auth_OpenID_TYPE_2_0_IDP;
    $uri = $this->getController()->genUrl('OpenID/index');
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

    echo $response->body;
    exit;
  }
}
