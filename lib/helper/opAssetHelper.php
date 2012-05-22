<?php

require_once sfConfig::get('sf_symfony_lib_dir').'/helper/AssetHelper.php';

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opAssetHelper provides helper function for Assets like css or javascript
 * this helpler refered to symfony's AssetHelper
 *
 * @package    OpenPNE
 * @subpackage helper
 * @author     Yuya Watanabe <watanabe@openpne.jp>
 */


/**
 * Returns <script> tags for all javascripts for smartphone pages added to the response object.
 *
 * you can use this helper to decide the location of javascripts in pages.
 * by default, if you don't call this helper, openpne will automatically include javascripts before </head>.
 * calling this helper disables this behavior.
 *
 * @return string <script> tags
 *
 * @see get_javascripts()
 */
function op_smt_get_javascripts()
{
  $response = sfContext::getInstance()->getResponse();
  sfConfig::set('symfony.asset.javascripts_included', true);

  $html = '';
  foreach ($response->getSmtJavascripts() as $file => $options)
  {
    $html .= javascript_include_tag($file, $options);
  }

  return $html;
}

/**
 * Prints <script> tags for all javascripts for smartphone pages added to the response object.
 *
 * @see get_javascripts()
 * @see op_smt_get_javascripts()
 */
function op_smt_include_javascripts()
{
  echo op_smt_get_javascripts();
}

/**
 * Returns <link> tags for all stylesheets smartphone pages added to the response object.
 *
 * You can use this helper to decide the location of stylesheets in pages.
 * By default, if you don't call this helper, openpne will automatically include stylesheets before </head>.
 * Calling this helper disables this behavior.
 *
 * @return string <link> tags
 *
 * @see get_stylesheets()
 */
function op_smt_get_stylesheets()
{
  $response = sfContext::getInstance()->getResponse();
  sfConfig::set('symfony.asset.stylesheets_included', true);

  $html = '';
  foreach ($response->getSmtStylesheets() as $file => $options)
  {
    $html .= stylesheet_tag($file, $options);
  }

  return $html;
}

/**
 * Prints <link> tags for all stylesheets for smartphone pages added to the response object.
 *
 * @see get_stylesheets()
 * @see op_smt_get_stylesheets()
 */
function op_smt_include_stylesheets()
{
  echo op_smt_get_stylesheets();
}


/**
 * Adds a stylesheet for smartphone pages to the response object.
 *
 * @see opWebResponse->addSmtStylesheet()
 */
function op_smt_use_stylesheet($css, $position = '', $options = array())
{
  sfContext::getInstance()->getResponse()->addSmtStylesheet($css, $position, $options);
}

/**
 * Adds a javascript for smartphone pages to the response object.
 *
 * @see opWebResponse->addSmtJavascript()
 */
function op_smt_use_javascript($js, $position = '', $options = array())
{
  sfContext::getInstance()->getResponse()->addSmtJavascript($js, $position, $options);
}
