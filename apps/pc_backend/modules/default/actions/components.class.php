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
  public function executeSideMenu()
  {
    $this->navs = Doctrine::getTable('Navigation')->retrieveByType('backend_side');
  }
}

