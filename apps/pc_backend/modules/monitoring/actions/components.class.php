<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * monitoring components.
 *
 * @package    OpenPNE
 * @subpackage admin
 * @author     Shinichi Urabe <urabe@tejimaya.com>
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class monitoringComponents extends sfComponents
{
  public function executeSubmenu(sfRequest $request)
  {
    $this->menu = include(sfContext::getInstance()->getConfigCache()->checkConfig('config/monitoring.yml'));
  }
}

