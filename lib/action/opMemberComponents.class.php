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
  public function executeActivityBox(sfWebRequest $request)
  {
    $id = $request->getParameter('id', $this->getUser()->getMemberId());
    $this->activities = Doctrine::getTable('ActivityData')->getActivityList($id, null, $this->gadget->getConfig('row'));
    $this->member = Doctrine::getTable('Member')->find($id);
    $this->isMine = ($id == $this->getUser()->getMemberId());
  }

  public function executeAllMemberActivityBox(sfWebRequest $request)
  {
    $this->activities = Doctrine::getTable('ActivityData')->getAllMemberActivityList($this->gadget->getConfig('row'));
    if ($this->gadget->getConfig('is_viewable_activity_form') && opConfig::get('is_allow_post_activity'))
    {
      $this->form = new ActivityDataForm();
    }
  }

  public function executeBirthdayBox(sfWebRequest $request)
  {
    $id = $request->getParameter('id', $this->getUser()->getMemberId());
    $birthday = Doctrine::getTable('MemberProfile')->getViewableProfileByMemberIdAndProfileName($id, 'op_preset_birthday');
    $this->targetDay = $birthday ? opToolkit::extractTargetDay((string)$birthday) : false;
  }

  public function executeAccessBlockList(sfWebRequest $request)
  {
    if (!$this->size)
    {
      if (sfConfig::get('sf_app') == 'mobile_frontend')
      {
        $this->size = 10;
      }
      else
      {
        $this->size = 20;
      }
    }

    $this->accessBlockPager =
      Doctrine::getTable('MemberRelationship')->getAccessBlockListPager(
      $this->getUser()->getMemberId(),
      $request->getParameter('page', 1),
      $this->size
    );
  }
}
