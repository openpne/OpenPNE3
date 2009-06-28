<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opDoctrineRecord
 *
 * @package    OpenPNE
 * @subpackage util
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
abstract class opDoctrineRecord extends sfDoctrineRecord
{
  public function save(Doctrine_Connection $conn = null)
  {
    if (is_null($conn))
    {
      $conn = opDoctrineQuery::getMasterConnection();
    }

    parent::save($conn);
  }
}
