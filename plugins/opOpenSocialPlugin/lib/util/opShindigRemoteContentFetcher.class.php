<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opShindigRemoteContentFetcher
 *
 * @package    opOpenSocialPlugin
 * @subpackage util
 * @author     Shogo Kawahara <kawahara@tejimaya.net>
 */
class opShindigRemoteContentFetcher extends RemoteContentFetcher {

  public function fetchRequest(RemoteContentRequest $request)
  {
    $outHeaders = array();
    if ($request->hasHeaders())
    {
      $headers = explode("\n", $request->getHeaders());
      foreach ($headers as $header)
      {
        if (strpos($header, ':'))
        {
          $key = trim(substr($header, 0, strpos($header, ':')));
          $val = trim(substr($header, strpos($header, ':') + 1));
          if (strcmp($key, "User-Agent") != 0 && 
            strcasecmp($key, "Transfer-Encoding") != 0 && 
            strcasecmp($key, "Cache-Control") != 0 && 
            strcasecmp($key, "Expries") != 0 && 
            strcasecmp($key, "Content-Length") != 0)
          {
            $outHeaders[$key] = $val;
          }
        }
      }
    }
    $outHeaders['User-Agent'] = "Shindig PHP";
    $options = array();
    $options['timeout'] = Shindig_Config::get('curl_connection_timeout');

    // configure proxy
    $proxyUrl   = Shindig_Config::get('proxy');
    if (!empty($proxyUrl))
    {
      $options['adapter'] = 'Zend_Http_Client_Adapter_Proxy';
      $proxy = parse_url($proxyUrl);
      if (isset($proxy['host']))
      {
        $options['proxy_host'] = $proxy['host'];
      }

      if (isset($proxy['port']))
      {
        $options['proxy_port'] = $proxy['port'];
      }

      if (isset($proxy['user']))
      {
        $options['proxy_user'] = $proxy['user'];
      }

      if (isset($proxy['pass']))
      {
        $options['proxy_pass'] = $proxy['pass'];
      }

    }

    $client = new Zend_Http_Client();
    $client->setConfig($options);
    $client->setUri($request->getUrl());

    if ($request->isPost())
    {
      $outPostBody = array();
      $postBodys = explode('&',$request->getPostBody());
      foreach ($postBodys as $postBody)
      {
        $pb = explode("=",urldecode($postBody));
        if (count($pb) == 2)
        {
          $outPostBody[$pb[0]] = $pb[1];
        }
      }
      $client->setParameterPost($outPostBody);
      $client->setMethod(Zend_Http_Client::POST);
    }
    else
    {
      $client->setMethod(Zend_Http_Client::GET);
    }

    $response = $client->request();

    $request->setHttpCode($response->getStatus());
    $request->setContentType($response->getHeader('Content-Type'));
    $request->setResponseHeaders($response->getHeaders());
    $request->setResponseContent($response->getBody());
    $request->setResponseSize(strlen($response->getBody()));
    return $request;
  }

  public function multiFetchRequest(Array $requests)
  {
    foreach($requests as $request)
    {
      $request = $this->fetchRequest($request);
    }
    return $requests;
  }
}
