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
  foreach (sfConfig::get('sf_openpne_disabled_plugins', array()) as $pluginName) {
    if (0 === strpos($directory, sfConfig::get('sf_plugins_dir') . DIRECTORY_SEPARATOR . $pluginName)) {
      return true;
    }
  }

  return false;
}

