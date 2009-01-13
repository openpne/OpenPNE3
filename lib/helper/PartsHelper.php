<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * PartsHelper.
 *
 * @package    openpne
 * @subpackage helper
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */

/**
 * Includes a login parts.
 *
 * @param string $id
 * @param sfForm $form
 * @param string $link_to   A location of an action.
 *
 * @see    include_partial
 */
function include_login_parts($id, $form, $link_to)
{
  $params = array(
    'id' => $form->getAuthMode().$id,
    'form' => $form,
    'link_to' => url_for(sprintf($link_to.'?%s=%s', sfOpenPNEAuthForm::AUTH_MODE_FIELD_NAME, $form->getAuthMode())),
  );
  include_partial('global/partsLogin', $params);
}

function include_page_title($title, $subtitle = '')
{
  $params = array(
    'title' => $title,
    'subtitle' => $subtitle,
  );
  include_partial('global/partsPageTitle', $params);
}

function include_list_box($id, $list, $options = array())
{
  $params = array(
    'id' => $id,
    'list' => $list,
    'options' => $options,
  );
  include_partial('global/partsListBox', $params);
}

function include_information_box($id, $body)
{
  $params = array(
    'id' => $id,
    'body' => $body,
  );
  include_partial('global/partsInformationBox', $params);
}

function include_alert_box($id, $body)
{
  $params = array(
    'id' => $id,
    'body' => $body,
  );
  include_partial('global/partsAlertBox', $params);
}

function include_box($id, $title = '', $body = '', $option = array())
{
  if (!empty($option['form']) && !isset($option['button'])) {
    $option['button'] = '変更';
  }

  if (!empty($option['form']) && !isset($option['url'])) {
    $request = sfContext::getInstance()->getRequest();
    $option['url'] = $request->getParameter('module').'/'.$request->getParameter('action');
  }

  if (!isset($option['padding']))
  {
    $option['padding'] = true;
  }

  $params = array(
    'id' => $id,
    'title' => $title,
    'body' => $body,
    'option' => $option,
  );

  include_partial('global/partsBox', $params);
}

function include_parts($parts_name, $id, $option = array())
{
  $params = array(
    'id'     => $id,
    'option' => $option,
  );
  include_partial('global/parts'.ucfirst($parts_name), $params);
}

/**
 * Gets customizes.
 *
 * @param string $id
 * @param string $name
 */
function get_customizes($id, $name, $vars = null)
{
  $context = sfContext::getInstance();

  $viewInstance = $context->get('view_instance');
  $customizes = $viewInstance->getCustomize('', $id, $name);
  $lastActionStack = $context->getActionStack()->getLastEntry();

  $content = '';
  foreach ($customizes as $customize) {
    $moduleName = $customize[0];
    if (!$moduleName) {
      $moduleName = $lastActionStack->getModuleName();
    }
    $actionName = $customize[1];
    if (!isset($vars))
    {
      $vars = $lastActionStack->getActionInstance()->getVarHolder()->getAll();
    }
    if ($customize[2])
    {
      $content .= get_component($moduleName, $actionName, $vars);
    }
    else
    {
      $templateName = $moduleName.'/'.$actionName;
      $content .= get_partial($templateName, $vars);
    }
  }

  return $content;
}

/**
 * Includes customizes.
 *
 * @param string $id
 * @param string $name
 */
function include_customizes($id, $name, $vars = null)
{
  echo get_customizes($id, $name, $vars);
}

function _is_disabled_plugin_dir($directory)
{
  foreach (sfConfig::get('sf_'.sfConfig::get('sf_app').'_openpne_disabled_plugins', array()) as $pluginName) {
    if (0 === strpos($directory, sfConfig::get('sf_plugins_dir') . DIRECTORY_SEPARATOR . $pluginName)) {
      return true;
    }
  }

  return false;
}

/**
 * Include news box
 *
 */
function include_news($id, $title = '', $list, $option = array())
{
  $option['padding'] = false;
  $option['parts'] = 'newsParts';
  $params = array(
    'list' => $list,
  );

 include_box( $id, $title, get_partial('global/partsNews', $params), $option);
}

/**
 * Include news pager
 *
 */
function include_news_pager($id, $title = '', $pager, $list, $link_to_detail)
{
  $params = array(
    'id' => $id,
    'title' => $title,
    'pager' => $pager,
    'list' => $list,
    'link_to_detail' => $link_to_detail,
  );

 include_partial('global/partsNewsPager', $params);
}

