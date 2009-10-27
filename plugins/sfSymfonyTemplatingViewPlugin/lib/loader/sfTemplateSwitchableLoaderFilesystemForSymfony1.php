<?php

/**
 * This file is part of the sfSymfonyTemplatingViewPlugin package.
 * (c) Kousuke Ebihara (http://co3k.org/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class sfTemplateSwitchableLoaderFilesystemForSymfony1 extends sfTemplateAbstractSwitchableLoader
{
  protected $templateDirs = array();

  public function configure()
  {
    $decoratorDirs = $this->context->getConfiguration()->getDecoratorDirs();
    foreach ($decoratorDirs as $k => $v)
    {
      $this->templateDirs[$k] = $v.'/%name%.%extension%';
    }
  }

  public function doLoad($template, $renderer = 'php')
  {
    $extension = $this->getParameter('extension', $this->view->getExtension());
    if ('.' === $extension[0])
    {
      $extension = substr($extension, 1);
    }

    if ('global' === $this->view->getModuleName())
    {
      $localDir = $this->context->getConfiguration()->getDecoratorDir($template.'.'.$extension);
    }
    else
    {
      $localDir = $this->context->getConfiguration()->getTemplateDir($this->view->getModuleName(), $template.'.'.$extension);
    }
    $this->view->setDirectory($localDir);

    $templateDirs = array_merge(array($localDir.'/%name%.%extension%'), $this->templateDirs);
    foreach ($templateDirs as $dir)
    {
      if (is_file($file = strtr($dir, array('%name%' => $template, '%extension%' => $extension))))
      {
        return new sfTemplateStorageFile($file, $renderer);
      }
    }
  }
}
