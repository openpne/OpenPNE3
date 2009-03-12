<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opPearRest interacts with a PEAR channel for OpenPNE plugin
 *
 * @package    OpenPNE
 * @subpackage plugin
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opPearRest extends sfPearRest
{
  public function retrieveData($url, $accept = false, $forcestring = false, $channel = false)
  {
    $content = parent::retrieveData($url, $accept, true, $channel);
    $result = $this->parseXML($content);
    return $result;
  }

  public function retrieveXml($url)
  {
    $content = $this->downloadHttp($url);
    $result = @simplexml_load_string($content);
    return $result;
  }

  public function saveCache($url, $contents, $lastmodified, $nochange = false, $cacheid = null)
  {
    if (!is_array($contents))
    {
      $contents = $this->parseXML($contents);
    }
    return parent::saveCache($url, $contents, $lastmodified, $nochange, $cacheid);
  }

  public function parseXML($content)
  {
    if (is_array($content))
    {
      return $content;
    }

    $parser = new PEAR_XMLParser();
    $parser->parse($content);
    return $parser->getData();
  }
}
