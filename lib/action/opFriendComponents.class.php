<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opFriendComponents
 *
 * @package    OpenPNE
 * @subpackage action
 * @author     Shogo Kawahara <kawahara@tejimaya.net>
 */
abstract class opFriendComponents extends sfComponents
{
  public function executeActivityBox()
  {
    $this->activities = Doctrine::getTable('ActivityData')->getFriendActivityList(null, $this->gadget->getConfig('row'));
    if (opConfig::get('is_allow_post_activity'))
    {
      $this->form = new ActivityDataForm();
    }
  }
}
