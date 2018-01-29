<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * message components.
 *
 * @package    OpenPNE
 * @subpackage message
 * @author     Maki Takahashi <maki@jobweb.co.jp>
 */
class opMessagePluginMessageComponents extends sfComponents
{
  public function executeUnreadMessage()
  {
    $this->unreadMessageCount = Doctrine::getTable('MessageSendList')->countUnreadMessage($this->getUser()->getMemberId());
  }
}
