<?php

class opTwigSandboxSecurityPolicy extends Twig_Sandbox_SecurityPolicy
{
  public function __construct(array $allowedTags = array(), array $allowedFilters = array(), array $allowedMethods = array(), array $allowedProperties = array())
  {
    parent::__construct($allowedTags, $allowedFilters, $allowedMethods, $allowedProperties);

    $this->allowedTags = array_merge($this->allowedTags, array('if', 'for'));
    $this->allowedFilters = array_merge($this->allowedFilters, array('date', 'encoding', 'default'));
  }

  public function checkMethodAllowed($obj, $method)
  {
  }

  public function checkPropertyAllowed($obj, $property)
  {
  }
}
