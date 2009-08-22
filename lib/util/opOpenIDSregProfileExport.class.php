<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opOpenIDSregProfileExport
 *
 * @package    OpenPNE
 * @subpackage util
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opOpenIDSregProfileExport extends opProfileExport
{
  public $tableToOpenPNE = array(
    'nickname' => 'name',
    'email'    => 'email',
    'fullname' => 'name',
    'dob'      => 'op_preset_birthday',
    'gender'   => 'op_preset_sex',
    'postcode' => 'op_preset_postal_code',
    'country'  => 'op_preset_country',
    'language' => 'language',
    'timezone' => 'time_zone',
  );

  public
    $profiles = array('dob', 'gender', 'postcode', 'country'),
    $names = array('nickname', 'fullname'),
    $configs = array('language', 'timezone'),
    $emails = array('email');

  public function getGender()
  {
    $sex = (string)$this->member->getProfile('op_preset_sex');
    if (!$sex)
    {
      return '';
    }

    return ('Man' === $sex ? 'M' : 'F');
  }

  public function getLanguage()
  {
    $language = $this->member->getConfig('language');

    return substr($language, 0, strpos($language, '_'));
  }
}
