<?php

/**
 * This file is part of the sfSymfonyTemplatingViewPlugin package.
 * (c) Kousuke Ebihara (http://co3k.org/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

if (!class_exists('sfCoreAutoload'))
{
  require_once(dirname(__FILE__).'/../../../../config/ProjectConfiguration.class.php');

  $dispatcher = new sfEventDispatcher();
  $logger = new sfCommandLogger($dispatcher);

  $application = new sfSymfonyCommandApplication($dispatcher, null, array('symfony_lib_dir' => '/tmp'));
}

