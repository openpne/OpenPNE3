<?php
/**
 * Script to test the output from Doctrine_Cli
 *
 * @author Dan Bettles <danbettles@yahoo.co.uk>
 */

require_once(dirname(dirname(dirname(__FILE__))) . '/lib/Doctrine/Core.php');
spl_autoload_register(array('Doctrine_Core', 'autoload'));

require_once(dirname(__FILE__) . '/TestTask02.php');

$cli = new Doctrine_Cli();
$cli->run($_SERVER['argv']);
