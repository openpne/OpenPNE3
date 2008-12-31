<?php

/**
 * opUtilHelper provides basic utility helper functions.
 *
 * @package    OpenPNE
 * @subpackage helper
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */

/**
 * Returns a navigation for paginated list.
 *
 * @param  sfPager $pager
 * @param  string  $link_to  A path to go to next/previous page.
                             "%d" will be converted to number of page.
 * @return string  A navigation for paginated list.
 */
function pager_navigation($pager, $link_to, $is_total = true)
{
  $navigation = '';

  if ($pager->haveToPaginate()) {
    if ($pager->getPreviousPage() != $pager->getPage()) {
      $navigation .= link_to('&lt;前', sprintf($link_to, $pager->getPreviousPage())) . '&nbsp;';
    }
  }

  if ($is_total) {
    $navigation .= pager_total($pager);
  }

  if ($pager->haveToPaginate()) {
    if ($pager->getNextPage() != $pager->getPage()) {
      $navigation .= '&nbsp;' . link_to('次&gt;', sprintf($link_to, $pager->getNextPage()));
    }
  }

  return $navigation;
}

function pager_total($pager)
{
  return sprintf('%d件～%d件を表示', $pager->getFirstIndice(), $pager->getLastIndice());
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
