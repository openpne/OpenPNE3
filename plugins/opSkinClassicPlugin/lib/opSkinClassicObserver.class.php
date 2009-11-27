<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class opSkinClassicObserver
{
  static public function appendCss($event)
  {
    $context = sfContext::getInstance();
    if ($context->getConfiguration() instanceof pc_frontendConfiguration)
    {
      $context->getResponse()->addStylesheet('/cache/css/'.opSkinClassicConfig::getCurrentTheme().'.css', sfWebResponse::LAST);
    }
  }

  static public function cacheCss($event, $content)
  {
    $lastEntry = sfContext::getInstance()->getActionStack()->getLastEntry();
    if (!$lastEntry)
    {
      return $content;
    }

    if ('opSkinClassicPlugin' === $lastEntry->getModuleName() && 'css' === $lastEntry->getActionName())
    {
      $filesystem = new sfFilesystem();
      $dir = sfConfig::get('sf_web_dir').DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR.'css';
      @$filesystem->mkdirs($dir);
      file_put_contents($dir.DIRECTORY_SEPARATOR.opSkinClassicConfig::getCurrentTheme().'.css', $content);
    }

    return $content;
  }
}
