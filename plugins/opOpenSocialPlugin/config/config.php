<?php
sfToolkit::addIncludePath(array(
  //Shindig
  dirname(__FILE__).'/../lib/vendor/Shindig/',
  dirname(__FILE__).'/../lib/vendor/PEAR/',
));
$this->dispatcher->connect('routing.load_configuration', array('opOpenSocialPluginRouting', 'listenToRoutingLoadConfigurationEvent'));
