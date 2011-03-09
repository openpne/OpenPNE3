<?php
/**
 * ExtraFunctions extension
 *
 * This source file is subject to the Apache License version 2.0
 * that is bundled with this package in the file LICENSE.
 *
 * @license     Apache License 2.0
 */

/**
 * Doctrine_Expression_Sqlite_ExtraFunctions
 *
 * @package     ExtraFunctions
 * @subpackage  Expression
 * @author      Kimura Youichi <kim.upsilon@bucyou.net>
 */
class Doctrine_Expression_Sqlite_ExtraFunctions extends Doctrine_Expression_Sqlite
{
    public function date_format($datetime, $format)
    {
        return 'strftime(' . $format . ', ' . $datetime . ')';
    }
}
