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
 * @author     Ogawa Rimpei <ogawa@tejimaya.com>
 */

/**
 * Include parts
 *
 * @param string $name parts name
 * @param string $id
 * @param mixed  $content
 * @param array  $options
 */
function op_include_parts($name, $id, $content, $options = array())
{
  $params = array(
    'id'      => $id,
    'name'    => $name,
    'content' => $content,
    'options' => $options,
  );

  if ($name)
  {
    $params['op_content'] = get_partial('global/parts'.ucfirst($name), $params);
  }
  else
  {
    $params['op_content'] = $content;
  }

  include_partial('global/partsLayout', $params);
}

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
  op_include_parts('listBox', $id, $list, $options);
}

function include_information_box($id, $body)
{
  op_include_parts('informationBox', $id, $body);
}

function include_alert_box($id, $body)
{
  op_include_parts('alertBox', $id, $body);
}

function include_simple_box($id, $title = '', $block = '', $option = array())
{
  if(!isset($option['border']))
  {
    $option['border'] = true;
  }
  if(!isset($option['class']))
  {
    $option['class'] = '';
  }

  $params = array(
    'id' => $id,
    'title' => $title,
    'block' => $block,
    'option' => $option,
  );

  include_partial('global/partsSimpleBox', $params);
}

function include_box($id, $title = '', $body = '', $options = array())
{
  $options['title'] = $title;

  if (!empty($options['form']))
  {
    if (!isset($options['button']))
    {
      $options['button'] = '変更';
    }

    if (!isset($options['url']))
    {
      $request = sfContext::getInstance()->getRequest();
      $options['url'] = $request->getParameter('module').'/'.$request->getParameter('action');
    }

    op_include_parts('form', $id, $options['form'], $options);
  }
  else
  {
    op_include_parts('box', $id, $body, $options);
  }
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
 * Include news
 *
 */
function include_news($id, $title = '', $list, $option = array())
{
  $option['class'] = 'partsNews';
  $params = array(
    'list' => $list,
  );

 include_simple_box( $id, $title, get_partial('global/partsNews', $params), $option);
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

 include_simple_box( $id, $title, get_partial('global/partsNewsPager', $params), array('class' => 'partsNewsPager'));
}

