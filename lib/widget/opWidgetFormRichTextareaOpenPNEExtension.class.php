<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opWidgetFormRichTextareaOpenPNEExtension
 *
 * @package    OpenPNE
 * @subpackage widget
 * @author     Shogo Kawahara <kawahara@tejimaya.net>
 */
abstract class opWidgetFormRichTextareaOpenPNEExtension
{
  static public function getPlugins()
  {
    return array();
  }

  static public function getButtons()
  {
    return array();
  }

  static public function getButtonOnClickActions()
  {
    return array();
  }

  static public function getConvertCallbacks()
  {
    return array();
  }

  static public function getHtmlConverts()
  {
    return array();
  }

  static public function configure(&$configs)
  {
  }
}
