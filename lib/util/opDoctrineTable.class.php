<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opDoctrineTable
 *
 * @package    OpenPNE
 * @subpackage util
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opDoctrineTable extends Doctrine_Table
{
  public function construct()
  {
    $result = parent::construct();

    if (!sfContext::hasInstance())
    {
      return $result;
    }

    if (!($this->_conn->getAttribute(Doctrine::ATTR_EXPORT) & Doctrine::EXPORT_CONSTRAINTS))
    {
      $this->addRecordListener(new opApplicationLevelCascadingListener());
    }

    return $result;
  }
}
