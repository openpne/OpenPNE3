<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opGlobalPartialView
 *
 * @package    OpenPNE
 * @subpackage view
 * @author     ShogoKawahara <kawahara@tejimaya.net>
 */
class opGlobalPartialView extends sfPartialView
{
 /**
  * Configure template for this view
  *
  */
  public function configure()
  {
    $this->setDecorator(false);
    $this->setTemplate($this->actionName.$this->getExtension());
    $this->setDirectory($this->context->getConfiguration()->getGlobalTemplateDir($this->getTemplate()));
  }
}
