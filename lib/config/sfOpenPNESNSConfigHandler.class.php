<?php

/**
 * sfOpenPNESNSConfigHandler
 *
 * @package    OpenPNE
 * @subpackage config
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class sfOpenPNESNSConfigHandler extends sfOpenPNEConfigHandler
{
  protected $prefix = 'openpne_sns_';
  protected $databaseManager;

  protected function convertConfig($key, $value)
  {
    $this->initializeDatabaseManager();

    $value['value'] = SnsConfigPeer::retrieveByName($key);
    if (is_null($value['value'])) {
      $value['value'] = $value['default'];
    }

    return $value;
  }

  protected function getApplication()
  {
    return $this->getParameterHolder()->get('application');
  }

  protected function initializeDatabaseManager()
  {
    if (!$this->databaseManager) {
      $this->databaseManager = new sfDatabaseManager($this->getApplication());
      $this->databaseManager->loadConfiguration();
    }
  }
}
