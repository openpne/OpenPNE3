<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class opOpenSocialPlugin3_ChangeColumnsForDoctrine extends Doctrine_Migration_Base
{
  public function up()
  {
    // application
    $this->removeColumn('application', 'updated_at');

    // application_i18n
    $this->renameColumn('application_i18n', 'culture', 'lang');
    $this->removeColumn('application_i18n', 'version');
    $this->addColumn('application_i18n', 'created_at', 'timestamp', '25');
    $this->addColumn('application_i18n', 'updated_at', 'timestamp', '25');

    // member_application
    $this->removeColumn('member_application', 'is_disp_other');
    $this->removeColumn('member_application', 'is_disp_home');
    $this->removeColumn('member_application', 'is_gadget');
    $this->addColumn('member_application', 'public_flag', 'enum', null, array(
      'values'  => array('public', 'friends', 'private'),
      'default' => 'public', 
    ));
  }

  public function down()
  {
  }
}
