<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class renameColumnsForDoctrine extends Doctrine_Migration_Base
{
  public function up()
  {
    // community_category
    $this->renameColumn('community_category', 'lft_key', 'lft');
    $this->renameColumn('community_category', 'rht_key', 'rgt');

    // member_profile
    $this->renameColumn('member_profile', 'lft_key', 'lft');
    $this->renameColumn('member_profile', 'rht_key', 'rgt');

    // navigation_i18n
    $this->renameColumn('navigation_i18n', 'culture', 'lang');

    // profile_i18n
    $this->renameColumn('profile_i18n', 'culture', 'lang');

    // profile_option_i18n
    $this->renameColumn('profile_option_i18n', 'culture', 'lang');
  }

  public function down()
  {
    // community_category
    $this->renameColumn('community_category', 'lft', 'lft_key');
    $this->renameColumn('community_category', 'rgt', 'rht_key');

    // member_profile
    $this->renameColumn('member_profile', 'lft', 'lft_key');
    $this->renameColumn('member_profile', 'rgt', 'rht_key');

    // navigation_i18n
    $this->renameColumn('navigation_i18n', 'lang', 'culture');

    // profile_i18n
    $this->renameColumn('profile_i18n', 'lang', 'culture');

    // profile_option_i18n
    $this->renameColumn('profile_option_i18n', 'lang', 'culture');
  }
}
