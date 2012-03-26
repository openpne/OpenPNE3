<?php

/**
 * This file is part of the sfSymfonyTemplatingViewPlugin package.
 * (c) Kousuke Ebihara (http://co3k.org/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

abstract class sfTemplateAbstractSwitchableLoader extends sfTemplateLoader
{
  protected
    $context = null,
    $view = null,
    $configure = array();

  public function __construct(sfView $view, sfContext $context, array $configure = array())
  {
    $this->context = $context;
    $this->view = $view;
    $this->configure = $configure;

    $this->configure();
  }

  public function getParameter($name, $default = null)
  {
    if (isset($this->configure[$name]))
    {
      return $this->configure[$name];
    }

    return $default;
  }

  public function configure()
  {
  }

  final public function load($template, $renderer = 'php')
  {
    $result = $this->doLoad($template, $renderer);
    if ($result)
    {
      return $result;
    }

    return false;
  }

  abstract public function doLoad($template, $renderer = 'php');
}
