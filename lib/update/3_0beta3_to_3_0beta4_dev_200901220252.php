<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class opUpdate_3_0beta3_to_3_0beta4_dev_200901220252 extends opUpdate
{
  public function update()
  {
    $this->changeColumn('member', 'name', 'string', array('notnull' => true, 'default' => '', 'length' => 64));
    $this->changeColumn('member', 'is_active', 'boolean', array('notnull' => true, 'default' => 0));

    $this->changeColumn('file', 'name', 'string', array('notnull' => true, 'default' => '', 'length' => 64));
    $this->changeColumn('file', 'type', 'string', array('notnull' => true, 'default' => '', 'length' => 64));

    $this->changeColumn('profile', 'name', 'string', array('notnull' => true, 'default' => '', 'length' => 64));
    $this->changeColumn('profile', 'is_required', 'boolean', array('notnull' => true, 'default' => 0));
    $this->changeColumn('profile', 'is_unique', 'boolean', array('notnull' => true, 'default' => 0));
    $this->changeColumn('profile', 'form_type', 'string', array('notnull' => true, 'default' => '', 'length' => 32));
    $this->changeColumn('profile', 'value_type', 'string', array('notnull' => true, 'default' => '', 'length' => 32));
    $this->changeColumn('profile', 'is_disp_regist', 'boolean', array('notnull' => true, 'default' => 0));
    $this->changeColumn('profile', 'is_disp_config', 'boolean', array('notnull' => true, 'default' => 0));
    $this->changeColumn('profile', 'is_disp_search', 'boolean', array('notnull' => true, 'default' => 0));

    $this->changeColumn('member_profile', 'lft_key', 'integer', array('default' => 0));
    $this->changeColumn('member_profile', 'rht_key', 'integer', array('default' => 0));

    $this->changeColumn('community', 'name', 'string', array('notnull' => true, 'default' => '', 'length' => 64));

    $this->changeColumn('community_member', 'position', 'string', array('notnull' => true, 'default' => '', 'length' => 32));

    $this->changeColumn('admin_user', 'username', 'string', array('notnull' => true, 'default' => '', 'length' => 64));
    $this->changeColumn('admin_user', 'password', 'string', array('notnull' => true, 'default' => '', 'length' => 64));

    $this->changeColumn('sns_config', 'name', 'string', array('notnull' => true, 'default' => '', 'length' => 64));

    $this->changeColumn('member_config', 'name', 'string', array('notnull' => true, 'default' => '', 'length' => 64));

    $this->changeColumn('community_config', 'name', 'string', array('notnull' => true, 'default' => '', 'length' => 64));

    $this->renameTable('home_widget', 'gadget');
    $this->changeColumn('gadget', 'type', 'string', array('notnull' => true, 'default' => '', 'length' => 64));
    $this->changeColumn('gadget', 'name', 'string', array('notnull' => true, 'default' => '', 'length' => 64));

    $this->renameTable('home_widget_config', 'gadget_config');
    $this->dropForeignKey('gadget_config', 'home_widget_config_FK_1');
    $this->renameColumn('gadget_config', 'home_widget_id', 'gadget_id');
    $this->changeColumn('gadget_config', 'name', 'string', array('notnull' => true, 'default' => '', 'length' => 64));

    $this->renameTable('navi', 'navigation');
    $this->changeColumn('navigation', 'type', 'string', array('notnull' => true, 'default' => '', 'length' => 64));

    $this->renameTable('navi_i18n', 'navigation_i18n');

    $this->changeColumn('blacklist', 'uid', 'string', array('notnull' => true, 'default' => '', 'length' => 32));
  }
}
