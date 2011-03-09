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
 * Doctrine_Expression_Pgsql_ExtraFunctions
 *
 * @package     ExtraFunctions
 * @subpackage  Expression
 * @author      Kimura Youichi <kim.upsilon@bucyou.net>
 */
class Doctrine_Expression_Pgsql_ExtraFunctions extends Doctrine_Expression_Pgsql
{
    public function date_format($datetime, $format)
    {
        $format = str_replace(array('%m', '%d'), array('MM', 'DD'), $format);

        return 'TO_CHAR(' . $datetime . ', ' . $format . ')';
    }
}
