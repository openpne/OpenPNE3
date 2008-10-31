<?php

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

/**
 * Includes customizes.
 *
 * @param string $id
 * @param string $name
 */
function include_customizes($id, $name)
{
  $context = sfContext::getInstance();

  $viewInstance = $context->get('view_instance');
  $customizes = $viewInstance->getCustomize('', $id, $name);

  $content = '';
  foreach ($customizes as $customize) {
    $moduleName = $customize[0];
    if (!$moduleName) {
      $moduleName = $context->getActionStack()->getLastEntry()->getModuleName();
    }
    $actionName = '_'.$customize[1];
    $view = new sfPartialView($context, $moduleName, $actionName, '');
    if (_is_disabled_plugin_dir($view->getDirectory())) {
      continue;
    }
    $content .= $view->render();
  }

  echo $content;
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

