<?php

require_once 'PEAR/REST/13.php';

class sfPearRest13 extends PEAR_REST_13
{
  public function __construct($config, $options = array())
  {
    $class = isset($options['base_class']) ? $options['base_class'] : 'sfPearRest';

    $this->_rest = new $class($config, $options);
  }
}
