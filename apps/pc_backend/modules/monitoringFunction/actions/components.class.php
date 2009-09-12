<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * monitoringFunction components.
 *
 * @package    OpenPNE
 * @subpackage admin
 * @author     Shinichi Urabe <urabe@tejimaya.com>
 */
class monitoringFunctionComponents extends sfComponents
{
  public function executeSubMenu(sfRequest $request)
  {
    $this->navs = Doctrine::getTable('Navigation')->retrieveByType('monitoring_function_submenu');
  }
}

