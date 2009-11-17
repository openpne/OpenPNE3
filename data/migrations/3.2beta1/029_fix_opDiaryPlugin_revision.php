<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class Revision29_FixOpDiaryPluginRevision extends Doctrine_Migration_Base
{
  public function up()
  {
    // and try to fix community topic revision if the plugin is exists
    $conn = Doctrine_Manager::getInstance()->getConnectionForComponent('SnsConfig');
    $result = $conn->fetchOne('SELECT value FROM sns_config WHERE name = ?', array('opDiaryPlugin_revision'));
    if (!$result)
    {
      Doctrine::getTable('SnsConfig')->set('opDiaryPlugin_revision', '3');
    }
  }
}
