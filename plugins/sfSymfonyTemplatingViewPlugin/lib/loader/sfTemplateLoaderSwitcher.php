<?php

/**
 * This file is part of the sfSymfonyTemplatingViewPlugin package.
 * (c) Kousuke Ebihara (http://co3k.org/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class sfTemplateLoaderSwitcher extends sfTemplateLoader
{
  protected $loaders = array();

  public function __construct(array $rules = array(), sfView $view, sfContext $context)
  {
    if (empty($rules['php']))
    {
      throw new LogicException('The "php" rule must be defined.');
    }

    foreach ($rules as $key => $rule)
    {
      $this->loaders[$key] = array();

      foreach ($rule as $k => $v)
      {
        if (empty($v['loader']) || !is_subclass_of($v['loader'], 'sfTemplateAbstractSwitchableLoader'))
        {
          throw new LogicException('The specified loader is invalid.');
        }

        $this->loaders[$key][] = new $v['loader']($view, $context, $v);
      }
    }
  }

  public function load($template, $renderer = 'php')
  {
    if (empty($this->loaders[$renderer]))
    {
      throw new LogicException(sprintf('The specified loader name "%s" is not defined.', $renderer));
    }

    $loaders = $this->loaders[$renderer];
    foreach ($loaders as $loader)
    {
      $_renderer = $loader->getParameter('renderer', 'php');
      if (false !== $content = $loader->load($template, $_renderer))
      {
        return $content;
      }
    }

    return false;
  }
}
