<?php

/*
 * This file is part of the symfony package.
 * (c) 2004-2006 Fabien Potencier <fabien.potencier@symfony-project.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

class defaultActions extends sfActions
{
  public function executeIndex()
  {
    return sfView::NONE;
  }

  public function executeModule()
  {
    return sfView::NONE;
  }

  public function executeError404()
  {
    return sfView::NONE;
  }

  public function executeSecure()
  {
    return sfView::NONE;
  }

  public function executeLogin()
  {
    return sfView::NONE;
  }

  public function executeDisabled()
  {
    return sfView::NONE;
  }
}
