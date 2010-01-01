<?php

require_once dirname(__FILE__).'/../lib/vendor/symfony/lib/autoload/sfCoreAutoload.class.php';
sfCoreAutoload::register();

require_once dirname(__FILE__).'/../lib/config/opProjectConfiguration.class.php';

define('OPENPNE3_CONFIG_DIR', dirname(__FILE__));

class ProjectConfiguration extends opProjectConfiguration
{
  public function setupProjectOpenPNE()
  {
    // You can write your own configurations
  }

  public function setupProjectOpenPNEDoctrine($manager)
  {
    // You can write your own configurations.

    // In default, OpenPNE uses foreign key.
    // If you want not to use foreign key, comment out the following configuration:
    // $manager->setAttribute(Doctrine::ATTR_EXPORT, Doctrine::EXPORT_ALL ^ Doctrine::EXPORT_CONSTRAINTS);
  }

  public function setup()
  {
    // Do not edit this method if unsure
    parent::setup();
  }
}
