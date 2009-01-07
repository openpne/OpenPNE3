<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * Subclass for representing a row from the 'profile' table.
 *
 * 
 *
 * @package lib.model
 */ 
class Profile extends BaseProfile
{
	public function hydrate($row, $startcol = 0, $rehydrate = false)
  {
    $this->setCulture(sfContext::getInstance()->getUser()->getCulture());
    return parent::hydrate($row, $startcol, $rehydrate);
  }
}
