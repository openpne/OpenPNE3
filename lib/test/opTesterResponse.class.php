<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * functional test class for OpenPNE.
 *
 * @package    OpenPNE
 * @subpackage test
 * @author     Masato Nagasawa <nagasawa@tejimaya.com>
 */
class opTesterResponse extends sfTesterResponse
{
  public function initialize()
  {
    parent::initialize();
    if ($this->domCssSelector)
    {
      $this->domCssSelector = new opDomCssSelector($this->dom);
    }
  }
}
