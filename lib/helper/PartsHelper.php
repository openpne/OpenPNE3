<?php

/**
 * PartsHelper.
 *
 * @package    openpne
 * @subpackage helper
 * @author     Kousuke Ebihara <ebihara@tejimaya.net>
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
    'id' => $id,
    'form' => $form,
    'link_to' => $link_to,
  );
  include_partial('global/partsLogin', $params);
}

/**
 * Sets entry point.
 *
 * @param string $id
 * @param string $name
 */
function set_entry_point($id, $name)
{
  if (!has_slot($id . $name)) {
    include_entry_points($id, $name);
  }

  include_slot($id . $name);
}

/**
 * Includes entry points.
 *
 * @param string $id
 * @param string $name
 */
function include_entry_points($id, $name)
{
  $context = sfContext::getInstance();
  $lastActionStack = $context->getActionStack()->getLastEntry();
  $lastAction = $lastActionStack->getModuleName().'/'.$lastActionStack->getActionName();

  $viewInstance = sfContext::getInstance()->get('view_instance');
  $customizes = $viewInstance->getCustomize('', $id, $lastAction, $name);

  $content = '';
  foreach ($customizes as $customize) {
    $moduleName = $customize[0];
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
  foreach (sfConfig::get('sf_openpne_disabled_plugins', array()) as $pluginName) {
    if (0 === strpos($directory, sfConfig::get('sf_plugins_dir') . DIRECTORY_SEPARATOR . $pluginName)) {
      return true;
    }
  }

  return false;
}

