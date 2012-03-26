<?php
$this->dispatcher->connect('routing.load_configuration', array('sfImageHandlerRouting', 'listenToRoutingLoadConfigurationEvent'));
$this->dispatcher->connect('user.method_not_found', array('sfImageHandlerUser', 'listenToMethodNotFound'));
