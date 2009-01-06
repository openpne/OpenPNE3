<?php

/**
 * Copyright (C) 2005-2009 OpenPNE Project
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *  http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
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
