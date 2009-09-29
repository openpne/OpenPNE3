<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opWidgetFormSchemaFormatter
 *
 * @package    OpenPNE
 * @subpackage widget
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opWidgetFormSchemaFormatter extends sfWidgetFormSchemaFormatterTable
{
  public function generateLabelName($name)
  {
    $originalCallable = null;

    if (isset(self::$translationCallable[0]) && (self::$translationCallable[0] instanceof opI18N))
    {
      $originalCallable = clone self::$translationCallable[0];

      self::$translationCallable[0]->titleize = true;
    }

    $result = parent::generateLabelName($name);

    if ($originalCallable)
    {
      self::$translationCallable[0] = $originalCallable;
    }

    return $result;
  }
}
