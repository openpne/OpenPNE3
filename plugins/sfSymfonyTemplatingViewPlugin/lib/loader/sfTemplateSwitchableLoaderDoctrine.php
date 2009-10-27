<?php

/**
 * This file is part of the sfSymfonyTemplatingViewPlugin package.
 * (c) Kousuke Ebihara (http://co3k.org/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class sfTemplateSwitchableLoaderDoctrine extends sfTemplateAbstractSwitchableLoader
{
  public function doLoad($template, $renderer = 'php')
  {
    $model = $this->getParameter('model', 'Template');
    $q = Doctrine::getTable($model)->createQuery()
      ->where('name = ?', $template)
      ->andWhere('renderer = ?', $renderer);

    $string = $q->fetchOne();
    if (!(string)$string)
    {
      return false;
    }

    $result = new sfTemplateStorageString((string)$string, $renderer);

    return $result;
  }
}
