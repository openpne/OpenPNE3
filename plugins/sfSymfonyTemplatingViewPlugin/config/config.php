<?php

/**
 * This file is part of the sfSymfonyTemplatingViewPlugin package.
 * (c) Kousuke Ebihara (http://co3k.org/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

require_once dirname(__FILE__).'/../lib/vendor/SymfonyTemplating/sfTemplateAutoloader.php';
sfTemplateAutoloader::register();

require_once dirname(__FILE__).'/../lib/vendor/Twig/Autoloader.php';
Twig_Autoloader::register();

set_include_path(get_include_path()
.PATH_SEPARATOR.dirname(__FILE__).'/../lib/vendor/smarty2'
);

