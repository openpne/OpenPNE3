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
 * Doctrine_Connection_Mysql_ExtraFunctions
 *
 * @package     ExtraFunctions
 * @subpackage  Connection
 * @author      Kimura Youichi <kim.upsilon@bucyou.net>
 */
class Doctrine_Connection_Mysql_ExtraFunctions extends Doctrine_Connection_Mysql
{
    public function __get($name)
    {
        if ($name === 'expression') {
            static $expressionModule = null;
            if ($expressionModule === null) {
                $class = 'Doctrine_Expression_' . $this->getDriverName() . '_ExtraFunctions';
                $expressionModule = new $class($this);
            }

            return $expressionModule;
        }

        return parent::__get($name);
    }
}
