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
 * opToolkit provides basic utility methods for OpenPNE.
 *
 * @package    OpenPNE
 * @subpackage util
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opToolkit
{
 /**
  * Returns the list of mobile e-mail address domains.
  *
  * @return array
  */
  public static function getMobileMailAddressDomains()
  {
    return (array)include(sfContext::getInstance()->getConfigCache()->checkConfig('config/mobile_mail_domain.yml'));
  }

  /**
   * Checks if a string is a mobile e-mail address.
   *
   * @param string
   *
   * @return bool true if $string is valid mobile e-mail address and false otherwise.
   */
  public static function isMobileEmailAddress($string)
  {
    $pieces = explode('@', $string, 2);
    $domain = array_pop($pieces);
    
    return in_array($domain, self::getMobileMailAddressDomains());
  }

 /**
  * Takes a text that matched pattern and replaces it to a marker.
  *
  * Replaces text that matched $pattern to a marker.
  * This method returns replaced text and a correspondence table of marker and pre-convert text
  *
  * @param string $subject
  * @param array  $patterns
  *
  * @return array
  */
  public static function replacePatternsToMarker($subject, $patterns = array())
  {
    $i = 0;
    $list = array();

    if (empty($patterns))
    {
      $patterns = array(
        '/<input[^>]+>/is',
        '/<textarea.*?<\/textarea>/is',
        '/<option.*?<\/option>/is',
        '/<img[^>]+>/is',
        '/<head.*?<\/head>/is',
      );
    }

    foreach ($patterns as $pattern)
    {
      if (preg_match_all($pattern, $subject, $matches))
      {
        foreach ($matches[0] as $match)
        {
          $replacement = '<<<MARKER:'.$i.'>>>';
          $list[$replacement] = $match;
          $i++;
        }
      }
    }

    $subject = str_replace(array_values($list), array_keys($list), $subject);
    return array($list, $subject);
  }
}
