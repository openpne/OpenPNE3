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
 * @author     Rimpei Ogawa <ogawa@tejimaya.com>
 * @author     Shogo Kawahara <kawahara@tejimaya.net>
 */

/**
 * Include parts
 *
 * @param string $name parts name
 * @param string $id
 * @param array  $options
 */
function op_include_parts($name, $id, $options = array())
{
  $params = array(
    'id'      => $id,
    'name'    => $name,
    'options' => new opPartsOptionHolder($options),
  );

  $params['op_content'] = get_partial('global/parts'.ucfirst($name), $params);

  if ('' === trim($params['op_content']))
  {
    return;
  }

  include_partial('global/partsLayout', $params);

  $shorts = $params['options']->getShortRequiredOptions();
  if ($shorts)
  {
    throw new LogicException(sprintf('The %s parts requires the following options: \'%s\'.', $name, implode('\', \'', $shorts)));
  }
}

/**
 * Include box parts
 *
 * @param string $id
 * @param string $body
 * @param array  $options
 *
 * @see op_include_parts
 */
function op_include_box($id, $body, $options = array())
{
  $options['body'] = $body;

  op_include_parts('box', $id, $options);
}

/**
 * Include form parts
 *
 * @param string $id
 * @param mixed  $form  a sfForm object or an array of sfForm objects
 * @param array  $options
 *
 * @see op_include_parts
 */
function op_include_form($id, $form, $options = array())
{
  $options['form'] = $form;

  op_include_parts('form', $id, $options);
}

/**
 * Include list parts
 *
 * @param string $id
 * @param array  $list
 * @param array  $options
 *
 * @see op_include_parts
 */
function op_include_list($id, $list, $options = array())
{
  $options['list'] = $list;

  op_include_parts('list', $id, $options);
}

/**
 * Include line parts
 *
 * @param string $id
 * @param string $line
 * @param array  $options
 *
 * @see op_include_parts
 */
function op_include_line($id, $line, $options = array())
{
  $options['line'] = $line;

  op_include_parts('line', $id, $options);
}

/**
 * Include yesNo parts
 *
 * @params string $id
 * @params mixed  $yesForm a sfForm object or array of sfForm objects
 * @params mixed  $noForm  a sfForm object or array of sfForm objects
 * @params array  $options
 */
function op_include_yesno($id, $yesForm, $noForm, $options = array())
{
  $options['yes_form'] = $yesForm;
  $options['no_form'] = $noForm;

  op_include_parts('yesNo', $id, $options);
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

/**
 * Set the op_mobile_header slot
 *
 * @param string $title
 * @param string $subtitle
 */
function op_mobile_page_title($title, $subtitle = '')
{
  $params = array(
    'title' => sfOutputEscaper::unescape($title),
    'subtitle' => sfOutputEscaper::unescape($subtitle),
  );

  slot('op_mobile_header', get_partial('global/partsPageTitle', $params));
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
    'link_to' => url_for(sprintf($link_to.'?%s=%s', opAuthForm::AUTH_MODE_FIELD_NAME, $form->getAuthMode())),
  );
  include_partial('global/partsLogin', $params);
}

/**
 * @deprecated since 3.0beta4
 */
function include_page_title($title, $subtitle = '')
{
  use_helper('Debug');
  log_message('include_page_title() is deprecated.', 'err');

  $params = array(
    'title' => $title,
    'subtitle' => $subtitle,
  );
  include_partial('global/partsPageTitle', $params);
}

/**
 * @deprecated since 3.0beta4
 */
function include_list_box($id, $list, $options = array())
{
  use_helper('Debug');
  log_message('include_list_box() is deprecated.', 'err');

  $options['list'] = $list;

  op_include_parts('listBox', $id, $options);
}

/**
 * @deprecated since 3.0beta4
 */
function include_simple_box($id, $title = '', $block = '', $options = array())
{
  use_helper('Debug');
  log_message('include_simple_box() is deprecated.', 'err');

  if(!isset($options['border']))
  {
    $options['border'] = true;
  }
  if(!isset($options['class']))
  {
    $options['class'] = '';
  }

  $params = array(
    'id' => $id,
    'title' => $title,
    'block' => $block,
    'options' => $options,
  );

  include_partial('global/partsSimpleBox', $params);
}

/**
 * @deprecated since 3.0beta4
 */
function include_box($id, $title = '', $body = '', $options = array())
{
  use_helper('Debug');
  log_message('include_box() is deprecated.', 'err');

  $options['title'] = $title;

  if (!empty($options['form']))
  {
    if ($body)
    {
      $options['info'] = $body;
    }

    if (!isset($options['button']))
    {
      $options['button'] = '変更';
    }

    if (!isset($options['url']))
    {
      $request = sfContext::getInstance()->getRequest();
      $options['url'] = $request->getParameter('module').'/'.$request->getParameter('action');
    }
    else
    {
      $options['url'] = url_for($options['url']);
    }

    op_include_form($id, $options['form'], $options);
  }
  else
  {
    op_include_box($id, $body, $options);
  }
}

/**
 * @deprecated since 3.0beta4
 */
function include_parts($parts_name, $id, $option = array())
{
  use_helper('Debug');
  log_message('include_parts() is deprecated.', 'err');

  $params = array(
    'id'      => $id,
    'options' => $option,
  );
  include_partial('global/parts'.ucfirst($parts_name), $params);
}

/**
 * Include news pager
 *
 * @deprecated since 3.0beta4
 */
function include_news_pager($id, $title = '', $pager, $list, $link_to_detail)
{
  use_helper('Debug');
  log_message('include_news_pager() is deprecated.', 'err');

  $params = array(
    'id' => $id,
    'title' => $title,
    'pager' => $pager,
    'list' => $list,
    'link_to_detail' => $link_to_detail,
  );

 include_simple_box( $id, $title, get_partial('global/partsNewsPager', $params), array('class' => 'partsNewsPager'));
}
