<?php

class sfTemplateRendererTwig extends sfTemplateRenderer
{
  protected
    $loader = null,
    $environment = null;

  public function __construct(Twig_Loader $loader = null, Twig_Environment $environment = null)
  {
    $this->loader = $loader;
    if (!$this->loader)
    {
      $this->loader = new Twig_Loader_String();
    }

    $this->environment = $environment;
    if (!$this->environment)
    {
      $this->environment = new Twig_Environment($this->loader);
    }
  }

  public function evaluate(sfTemplateStorage $template, array $parameters = array())
  {
    if ($template instanceof sfTemplateStorageFile)
    {
      $body = file_get_contents((string)$template);
    }
    else if ($template instanceof sfTemplateStorageString)
    {
      $body = (string)$template;
    }

    $twigTpl = $this->environment->loadTemplate($body);

    return $twigTpl->render($parameters);
  }
}
