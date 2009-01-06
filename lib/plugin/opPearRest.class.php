<?php

/**
 * Copyright (C) 2005-2009 OpenPNE Project
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *  http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
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
