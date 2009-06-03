<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class addNavigationItemForBackend extends Doctrine_Migration_Base
{
  public function up()
  {
    Doctrine::loadData(sfConfig::get('sf_data_dir').'/fixtures/008_import_backend_navi_menu.yml', true);
  }

  public function down()
  {
  }
}
