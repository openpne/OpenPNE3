<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
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

  public static function isEnabledRegistration($mode = '')
  {
    $registration = opConfig::get('enable_registration');
    if ($registration == 3)
    {
      return true;
    }

    if (!$mode && $registration)
    {
      return true;
    }

    if ($mode == 'mobile' && $registration == 1)
    {
      return true;
    }

    if ($mode == 'pc' && $registration == 2)
    {
      return true;
    }

    return false;
  }

 /**
  * Unifys EOL characters in the string.
  *
  * @param string $string
  * @param string $eol
  *
  * @return string
  */
  public static function unifyEOLCharacter($string, $eol = "\n")
  {
    $eols = array("\r\n", "\r", "\n");
    if (!in_array($eol, $eols))
    {
      return $string;
    }

    // first, unifys to LF
    $string = str_replace("\r\n", "\n", $string);
    $string = str_replace("\r", "\n", $string);

    // second, unifys to specify EOL character
    if ($eol !== "\n")
    {
      $string = str_replace("\n", $eol, $string);
    }

    return $string;
  }

  public static function extractEnclosedStrings($string, $enclosure = '"')
  {
    $result = array('base' => $string, 'enclosed' => array());
    $enclosureCount = substr_count($string, $enclosure);

    for ($i = 0; $i < $enclosureCount; $i++)
    {
      $begin = strpos($string, $enclosure);
      $finish = strpos($string, $enclosure, $begin + 1);
      if ($begin !== false && $finish !== false)
      {
        $head = substr($string, 0, $begin - 1);
        $body = substr($string, $begin + 1, $finish - $begin - 1);
        $foot = substr($string, $finish + 1);

        $string = $head.$foot;
        $result['enclosed'][] = $body;
        $i++;
      }
    }

    $result['base'] = $string;
    return $result;
  }

  public static function generatePasswordString($length = 12, $is_use_mark = true)
  {
    $result = '';

    $str = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    if ($is_use_mark)
    {
      $str .= '#$-=?@[]_';
    }

    $range = strlen($str) - 1;

    while ($length > strlen($result))
    {
      $result .= $str[rand(0, $range)];
    }

    return $result;
  }

  public static function stripNullByteDeep($value)
  {
    return is_array($value) ?
      array_map(array('opToolkit', 'stripNullByteDeep'), $value) :
      (is_string($value) ? preg_replace("/[\x{0}-\x{08}\x{0b}-\x{1f}\x{7f}-\x{9f}\x{ad}]/u", '', $value) : $value);
  }

  public static function appendMobileInputModeAttributesForFormWidget(sfWidget $widget, $mode = 'alphabet')
  {
    $modes = array(
      'hiragana'    => 1,
      'hankakukana' => 2,
      'alphabet'    => 3,
      'numeric'     => 4,
    );

    if (empty($modes[$mode]))
    {
      return false;
    }

    $widget->setAttribute('istyle', $modes[$mode]);
    $widget->setAttribute('mode', $mode);
  }

/**
 * This method calculates how many days to go until specified day.
 *
 * @param string $targetDay
 * @return int between from target days.
 */
  public static function extractTargetDay($targetDay)
  {
    If (!$targetDay)
    {
      return -1;
    }

    list(, $m, $d) = explode('-', $targetDay);

    $m = (int)$m;
    $d = (int)$d;

    if ($m == 0 || $d == 0) {
      return -1;
    }

    $y = date('Y');

    $today = mktime(0, 0, 0);

    $theday_thisyear = mktime(0, 0, 0, $m, $d, $y);
    $theday_nextyear = mktime(0, 0, 0, $m, $d, $y + 1);

    if ($theday_thisyear < $today) {
      $theday_next = $theday_nextyear;
    } else {
      $theday_next = $theday_thisyear;
    }

    return ($theday_next - $today) / 86400;
  }

 public static function retrieveAPIList($isWithI18n = true)
 {
    $result = array();

    $context = sfContext::getInstance();
    $config = new sfRoutingConfigHandler();
    $currentApp = sfConfig::get('sf_app');
    $i18n = $context->getI18n();

    sfConfig::set('sf_app', 'api');
    $routing = new sfPatternRouting($context->getEventDispatcher());
    $routing->setRoutes($config->evaluate($context->getConfiguration()->getConfigPaths('config/routing.yml')));
    sfConfig::set('sf_app', $currentApp);

    $context->getEventDispatcher()->notify(new sfEvent($routing, 'routing.load_configuration'));
    $routes = $routing->getRoutes();

    foreach ($routes as $route)
    {
      if ($route instanceof opAPIRouteInterface)
      {
        $caption = $route->getAPICaption();

        if ($isWithI18n)
        {
          $caption = $i18n->__($caption, null, 'api');
        }

        $result[$route->getAPIName()] = $caption;
      }
    }

    return $result;
 }

  static public function getCultureChoices($cultures)
  {
    $choices = array();
    foreach ($cultures as $culture)
    {
      $c = explode('_', $culture);
      try
      {
        $cultureInfo = sfCultureInfo::getInstance($culture);
        $choices[$culture] = $cultureInfo->getLanguage($c[0]);
        if (isset($c[1]))
        {
          $choices[$culture] .= ' ('.$cultureInfo->getCountry($c[1]).')';
        }
      }
      catch (sfException $e)
      {
        $choices[$culture] = $culture;
      }
    }

    return $choices;
  }

  static public function getPresetProfileList()
  {
    $configPath = 'config/preset_profile.yml';
    sfContext::getInstance()->getConfigCache()->registerConfigHandler($configPath, 'sfSimpleYamlConfigHandler', array());
    $list = include(sfContext::getInstance()->getConfigCache()->checkConfig($configPath));

    return $list;
  }

  public static function arrayMapRecursive($callback, $array)
  {
    $result = array();

    foreach ($array as $key => $value)
    {
      $result[$key] = is_array($value) ? call_user_func(array('opToolkit', 'arrayMapRecursive'), $callback, $value) : call_user_func($callback, $value);
    }

    return $result;
  }

/**
 * This method file download.
 *
 * @param string $original_filename
 * @param bin $bin
 * @return none binaryFile
 */
  static public function fileDownload($original_filename, $bin)
  {
    if (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false) {
      $original_filename = mb_convert_encoding($original_filename, 'SJIS', 'UTF-8');
    }
    $original_filename = str_replace(array("\r", "\n"), '', $original_filename);

    header('Content-Disposition: attachment; filename="'.$original_filename.'"');
    header('Content-Length: '.strlen($bin));
    header('Content-Type: application/octet-stream');

    echo $bin;
    exit;
  }

  public static function isSecurePage()
  {
    $context = sfContext::getInstance();
    $action = $context->getActionStack()->getLastEntry()->getActionInstance();
    $credential = $action->getCredential();

    if (sfConfig::get('sf_login_module') === $context->getModuleName()
      && sfConfig::get('sf_login_action') === $context->getActionName())
    {
      return false;
    }

    if (sfConfig::get('sf_secure_module') == $context->getModuleName()
      && sfConfig::get('sf_secure_action') == $context->getActionName())
    {
      return false;
    }

    if (!$action->isSecure())
    {
      return false;
    }

    if ((is_array($credential) && !in_array('SNSMember', $credential))
      || (is_string($credential) && 'SNSMember' !== $credential))
    {
      return false;
    }

    return true;
  }

  public static function clearCache()
  {
    $filesystem = new sfFilesystem();
    $filesystem->remove(sfFinder::type('file')->discard('.sf')->in(sfConfig::get('sf_cache_dir')));
    @$filesystem->remove(sfFinder::type('file')->in(sfConfig::get('sf_web_dir').'/cache/'));
  }

  /**
   * calculateAge
   */
  public static function calculateAge($birthdayString)
  {
    $birthdayTime = strtotime($birthdayString);
    if (false === $birthdayTime)
    {
      return false;
    }

    $thisYear = intval(date('Y'));
    $today = intval(date('nd'));

    $age = $thisYear - date('Y', $birthdayTime);
    if ($today < date('n', $birthdayTime) * 100 + date('d', $birthdayTime)) $age--;

    return $age;
  }

  public static function createStringDsnFromArray($arrayDsn)
  {
    $result = array();

    $table = array(
      'host'        => 'hostspec',
      'port'        => 'port',
      'dbname'      => 'database',
      'unix_socket' => 'unix_socket',
    );

    if (!empty($arrayDsn['hostspec']))
    {
      $pieces = explode(':', $arrayDsn['hostspec']);

      $arrayDsn['hostspec'] = $pieces[0];
      if (isset($pieces[1]))
      {
        $arrayDsn['port'] = $pieces[1];
      }
    }

    if (!empty($arrayDsn['protocol']) && !empty($arrayDsn['proto_opts']))
    {
      if ('unix' === $arrayDsn['protocol'])
      {
        $arrayDsn['unix_socket'] = $arrayDsn['proto_opts'];
      }
    }

    foreach ($table as $k => $v)
    {
      if (isset($arrayDsn[$v]))
      {
        $result[] = $k.'='.$arrayDsn[$v];
      }
    }

    return $arrayDsn['phptype'].':'.implode(';', $result);
  }

  public static function calculateUsableMemorySize()
  {
    $limit = trim(ini_get('memory_limit'));
    if ('-1' === $limit)
    {
      return null;
    }

    $unit = strtolower($limit[strlen($limit)-1]);
    $units = array(
      'k' => 1,
      'm' => 2,
      'g' => 3,
    );

    if (isset($units[$unit]))
    {
      $limit *= pow(1024, $units[$unit]);
    }

    $usage = memory_get_usage();

    return ($limit - $usage);
  }

  /**
   * Generates a randomized hash (from Ethna 2.5.0)
   *
   * Licensed under The BSD License. Original is the Ethna_Util::getRandom() method.
   *
   * Copyright (c) 2004-2006, Masaki Fujimoto
   * All rights reserved.
   *
   * @author  Masaki Fujimoto <fujimoto@php.net>
   * @license http://www.opensource.org/licenses/bsd-license.php The BSD License
   *
   * @param  int    $length  Length of a hash
   * @return string
   */
  public function getRandom($length = 64)
  {
    static $srand = false;

    if ($srand == false)
    {
      list($usec, $sec) = explode(' ', microtime());
      mt_srand((float) $sec + ((float) $usec * 100000) + getmypid());
      $srand = true;
    }

    // Is the "open_basedir" is on, and accessing to /proc is allowed?
    // If the "open_basedir" is empty, this method consider that accessing to it is allowed.
    $devfile = '/proc/net/dev';
    $open_basedir_conf = ini_get('open_basedir');
    $devfile_enabled = (empty($open_basedir_conf)
      || (preg_match('#:/proc#', $open_basedir_conf) > 0
      ||  preg_match('#^/proc#', $open_basedir_conf) > 0));

    $value = '';
    for ($i = 0; $i < 2; $i++)
    {
      // for Linux
      if ($devfile_enabled && file_exists($devfile))
      {
        $rx = $tx = 0;
        $fp = fopen($devfile, 'r');
        if ($fp != null)
        {
          $header = true;
          while (feof($fp) === false)
          {
            $s = fgets($fp, 4096);
            if ($header)
            {
              $header = false;
              continue;
            }
            $v = preg_split('/[:\s]+/', $s);
            if (is_array($v) && count($v) > 10)
            {
              $rx += $v[2];
              $tx += $v[10];
            }
          }
        }
        $platform_value = $rx.$tx.mt_rand().getmypid();
      }
      else
      {
        $platform_value = mt_rand().getmypid();
      }
      $now = strftime('%Y%m%d %T');
      $time = gettimeofday();
      $v = $now.$time['usec'].$platform_value.mt_rand(0, time());
      $value .= md5($v);
    }

    if ($length < 64)
    {
      $value = substr($value, 0, $length);
    }

    return $value;
  }

  static public function writeCacheFile($pathToCacheFile, $content)
  {
    $filesystem = new sfFilesystem();

    $currentUmask = umask();
    umask(0000);

    $tmpFile = tempnam(dirname($pathToCacheFile), basename($pathToCacheFile));
    if (!$fp = @fopen($tmpFile, 'wb'))
    {
      throw new sfCacheException('Failed to write cache file.');
    }

    @fwrite($fp, $content);
    @fclose($fp);
    if (!@rename($tmpFile, $pathToCacheFile))
    {
      if ($filesystem->copy($tmpFile, $pathToCacheFile, array('override' => true)))
      {
        $filesystem->remove($tmpFile);
      }
    }

    $filesystem->chmod($pathToCacheFile, 0666);
    umask($currentUmask);
  }
}
