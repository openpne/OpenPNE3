<?php

/**
 * NotificationMail
 * 
 * @package    OpenPNE
 * @subpackage model
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class NotificationMail extends BaseNotificationMail
{
  public function __toString()
  {
    return $this->Translation[self::getDefaultCulture()]->template;
  }
}
