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
 * Doctrine_Expression_Mysql_ExtraFunctions
 *
 * @package     ExtraFunctions
 * @subpackage  Expression
 * @author      Kimura Youichi <kim.upsilon@bucyou.net>
 */
class Doctrine_Expression_Mysql_ExtraFunctions extends Doctrine_Expression_Mysql
{
    public function date_format($datetime, $format)
    {
        return 'DATE_FORMAT(' . $datetime . ', ' . $format . ')';
    }
}
