<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class defaultComponents extends sfComponents
{
  public function executeNav()
  {
    $this->navs = Doctrine::getTable('Navigation')->retrieveByType($this->type);
  }

  public function executeInformationBox()
  {
  }

  public function executeFreeAreaBox()
  {
  }

  public function executeFreeAreaMail()
  {
  }

  public function executeLoginFormBox()
  {
    $this->forms = $this->getUser()->getAuthForms();
  }

  public function executeHeaderGadgets()
  {
    $gadgets = Doctrine::getTable('Gadget')->retrieveGadgetsByTypesName('mobileHeader');
    $this->gadgets = $gadgets['mobileHeaderContents'];
  }

  public function executeFooterGadgets()
  {
    $gadgets = Doctrine::getTable('Gadget')->retrieveGadgetsByTypesName('mobileFooter');
    $this->gadgets = $gadgets['mobileFooterContents'];
  }

  public function executeSideBanner()
  {
  }
}
