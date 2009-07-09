<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opWidgetFormSelectFormatterMobile
 *
 * @package    OpenPNE
 * @subpackage widget
 * @author     Shogo Kawahara <kawahara@tejimaya.net>
 */
class opWidgetFormSelectFormatterMobile 
{
  public static function formatter($widget, $inputs)
  {
    $rows = array();
    foreach ($inputs as $input)
    {
      $rows[] = $input['input'].$widget->getOption('label_separator').$input['label'];
    }

    return implode($widget->getOption('separator'), $rows);
  }
}
