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
  $content = '';
  $extension = '.php';

  $moduleName = $context->getActionStack()->getLastEntry()->getModuleName();
  $templateName = '_' . $id . $name . $extension;
  $directories = $context->getConfiguration()->getTemplateDirs($moduleName);

  foreach ($directories as $directory) {
    $templatePath = $directory . DIRECTORY_SEPARATOR . $templateName;
    if (is_readable($templatePath)) {
      ob_start();
      ob_implicit_flush(0);
      require $templatePath;
      $content .= ob_get_clean();
    }
  }

  slot($id . $name);
  echo $content;
  end_slot();
}

