<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opMemberComponents
 *
 * @package    OpenPNE
 * @subpackage action
 * @author     Shogo Kawahara <kawahara@tejimaya.net>
 */
abstract class opMemberComponents extends sfComponents
{
  public function executeActivityBox(opWebRequest $request)
  {
    $id = $request->getParameter('id', $this->getUser()->getMemberId());
    $this->activities = Doctrine::getTable('ActivityData')->getActivityList($id, null, $this->gadget->getConfig('row'));
    $this->member = Doctrine::getTable('Member')->find($id);
    $this->isMine = ($id == $this->getUser()->getMemberId());
  }

  public function executeAllMemberActivityBox(opWebRequest $request)
  {
    $this->activities = Doctrine::getTable('ActivityData')->getAllMemberActivityList($this->gadget->getConfig('row'));
    if ($this->gadget->getConfig('is_viewable_activity_form') && opConfig::get('is_allow_post_activity'))
    {
      $this->form = new ActivityDataForm();
    }
  }

  public function executeBirthdayBox(opWebRequest $request)
  {
    $id = $request->getParameter('id', $this->getUser()->getMemberId());
    $birthday = Doctrine::getTable('MemberProfile')->getViewableProfileByMemberIdAndProfileName($id, 'op_preset_birthday');
    $this->targetDay = $birthday ? opToolkit::extractTargetDay((string)$birthday) : false;
  }
}
