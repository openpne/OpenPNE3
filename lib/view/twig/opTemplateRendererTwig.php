<?php

class opTemplateRendererTwig extends sfTemplateRendererTwig
{
  public function __construct(Twig_Loader $loader = null, Twig_Environment $environment = null)
  {
    parent::__construct($loader, $environment);
    $this->environment->addExtension(new HelperTwigExtension());
  }
}

