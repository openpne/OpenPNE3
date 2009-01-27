<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opUtilHelper provides basic utility helper functions.
 *
 * @package    OpenPNE
 * @subpackage helper
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 * @author     Rimpei Ogawa <ogawa@tejimaya.com>
 */

/**
 * Returns a navigation for paginated list.
 *
 * @param  sfPager $pager
 * @param  string  $link_to  A path to go to next/previous page.
                             "%d" will be converted to number of page.
 * @return string  A navigation for paginated list.
 */
function pager_navigation($pager, $link_to, $is_total = true, $query_string = '')
{
  $navigation = '';

  if ($pager->haveToPaginate()) {
    if ($pager->getPreviousPage() != $pager->getPage()) {
      $navigation .= link_to('&lt;前', sprintf($link_to, $pager->getPreviousPage()), array('query_string' => $query_string)) . '&nbsp;';
    }
  }

  if ($is_total) {
    $navigation .= pager_total($pager);
  }

  if ($pager->haveToPaginate()) {
    if ($pager->getNextPage() != $pager->getPage()) {
      $navigation .= '&nbsp;' . link_to('次&gt;', sprintf($link_to, $pager->getNextPage()), array('query_string' => $query_string));
    }
  }

  return $navigation;
}

function pager_total($pager)
{
  return sprintf('%d件～%d件を表示', $pager->getFirstIndice(), $pager->getLastIndice());
}

function cycle_vars($name, $items, $delimiter = ',')
{
  static $cycles = array();
  if (!isset($cycles[$name]))
  {
    $cycles[$name] = array(
      'count' => 0,
      'items' => explode($delimiter, $items),
    );
  }

  $items = $cycles[$name]['items'];

  $result = $items[$cycles[$name]['count']];
  $cycles[$name]['count']++;

  if ($cycles[$name]['count'] >= count($items))
  {
    $cycles[$name]['count'] = 0;
  }

  return $result;
}

/**
 * Returns a project URL is over an application.
 *
 * @see url_for()
 *
 * @param string $application
 *
 * @return string
 */
function app_url_for()
{
  $arguments = func_get_args();
  if (is_array($arguments[1]) || '@' == substr($arguments[1], 0, 1) || false !== strpos($arguments[1], '/'))
  {
    return call_user_func_array('_app_url_for_internal_uri', $arguments);
  }
  else
  {
    return call_user_func_array('_app_url_for_route', $arguments);
  }
}

function _app_url_for_route($application, $routeName, $params = array(), $absolute = false)
{
  $params = array_merge(array('sf_route' => $routeName), is_object($params) ? array('sf_subject' => $params) : $params);

  return _app_url_for_internal_uri($application, $params, $absolute);
}

function _app_url_for_internal_uri($application, $internal_uri, $absolute = false)
{
  // stores current states
  $current_application = sfContext::getInstance()->getConfiguration()->getApplication();
  $current_environment = sfContext::getInstance()->getConfiguration()->getEnvironment();
  $current_is_debug = sfContext::getInstance()->getConfiguration()->isDebug();
  $current_config = sfConfig::getAll();

  // computes a url
  if (sfContext::hasInstance($application))
  {
    $context = sfContext::getInstance($application);
    sfContext::switchTo($application);
  }
  else
  {
    $config = ProjectConfiguration::getApplicationConfiguration($application, $current_environment, $current_is_debug);
    $context = sfContext::createInstance($config, $application);
  }
  $is_strip_script_name = (bool)sfConfig::get('sf_no_script_name');
  $result_url = $context->getController()->genUrl($internal_uri, $absolute);

  // restores the previous states
  sfContext::switchTo($current_application);
  sfConfig::add($current_config);

  // replaces a script name
  $before_script_name = basename(sfContext::getInstance()->getRequest()->getScriptName());
  $after_script_name = _create_script_name($application, $current_environment);
  if ($is_strip_script_name)
  {
    $before_script_name = '/'.$before_script_name;
    $after_script_name = '';
  }
  return str_replace($before_script_name, $after_script_name, $result_url);
}

function _create_script_name($application, $environment)
{
  $script_name = $application;
  if ($environment !== 'prod')
  {
    $script_name .= '_'.$environment;
  }
  $script_name .= '.php';

  return $script_name;
}

function op_format_date($date, $format = 'd', $culture = null, $charset = null)
{
  use_helper('Date');

  if (!$culture)
  {
    $culture = sfContext::getInstance()->getUser()->getCulture();
  }

  switch ($format)
  {
    case 'XShortDate':
      switch ($culture)
      {
        case 'ja_JP':
          $format = 'MM/dd';
          break;
        default:
          $format = 'd';
          break;
      }
      break;
    case 'XShortDateJa':
      switch ($culture)
      {
        case 'ja_JP':
          $format = 'MM月dd日';
          break;
        default:
          $format = 'd';
          break;
      }
      break;
    case 'XDateTime':
      switch ($culture)
      {
        case 'ja_JP':
          $format = 'yyyy/MM/dd HH:mm';
          break;
        default:
          $format = 'f';
          break;
      }
      break;
    case 'XDateTimeJa':
      switch ($culture)
      {
        case 'ja_JP':
          $format = 'yyyy年MM月dd日 HH:mm';
          break;
        default:
          $format = 'f';
          break;
      }
      break;
    case 'XDateTimeJaBr':
      switch ($culture)
      {
        case 'ja_JP':
          $format = "yyyy年\nMM月dd日\nHH:mm";
          break;
        default:
          $format = 'f';
          break;
      }
      break;
    case 'XCalendarMonth':
      switch ($culture)
      {
        case 'ja_JP':
          $format = 'yyyy年M月';
          break;
        default:
          $format = 'MMMM yyyy';
          break;
      }
      break;
  }

  return format_date($date, $format, $culture, $charset);
}

function op_url_cmd($text)
{
  $url_pattern = '/https?:\/\/([a-zA-Z0-9\-.]+)\/?(?:[a-zA-Z0-9_\-\/.,:;~?@=+$%#!()]|&amp;)*/';

  return preg_replace_callback($url_pattern, '_op_url_cmd', $text);
}

function _op_url_cmd($matches)
{
  $url = $matches[0];
  $cmd = $matches[1];

  $file = $cmd . '.js';
  $path = './cmd/' . $file;

  if (!is_readable($path)) {
    return str_replace('&', '&amp;', op_auto_link_text(str_replace('&amp;', '&', $url)));
  }

  $public_path = _compute_public_path($cmd, 'cmd', 'js');
  $result = <<<EOD
<script type="text/javascript" src="{$public_path}"></script>
<script type="text/javascript">
<!--
url2cmd('{$url}');
//-->
</script>
EOD;
  return $result;
}

/**
 * @see auto_link_text
 */
function op_auto_link_text($text, $link = 'urls', $href_options = array('target' => '_blank'), $truncate = true, $truncate_len = 57, $pad = '...')
{
  use_helper('Text');
  return auto_link_text($text, $link, $href_options, $truncate, $truncate_len, $pad);
}

?>
