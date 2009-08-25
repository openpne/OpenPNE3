<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opAccessControlRecordInterface
 *
 * @package    OpenPNE
 * @subpackage util
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
interface opAccessControlRecordInterface
{
 /**
  * Generates and returns role ID that is specified the instance of Zend_Acl_Role.
  *
  * It generates a role of the specified member that is from the record.
  *
  * @return string
  */
  public function generateRoleId(Member $member);
}
