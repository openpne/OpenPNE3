<?php

/**
 * member actions.
 *
 * @package    OpenPNE
 * @subpackage member
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class memberActions extends sfOpenPNEMemberAction
{
 /**
  * Executes home action
  *
  * @param sfRequest $request A request object
  */
  public function executeHome($request)
  {
    $this->information = SnsConfigPeer::retrieveByName('mobile_home_information');
    return parent::executeHome($request);
  }

 /**
  * Executes configUID action
  *
  * @param sfRequest $request A request object
  */
  public function executeConfigUID($request)
  {
    $option = array('member' => $this->getUser()->getMember());
    $this->passwordForm = new sfOpenPNEPasswordForm(array(), $option);

    if ($request->isMethod('post')) {
      $this->passwordForm->bind($request->getParameter('password'));
      if ($this->passwordForm->isValid()) {
        $memberConfig = MemberConfigPeer::retrieveByNameAndMemberId('mobile_uid', $this->getUser()->getMemberId());
        if (!$memberConfig) {
          $memberConfig = new MemberConfig();
          $memberConfig->setMember($this->getUser()->getMember());
          $memberConfig->setName('mobile_uid');
        }
        $memberConfig->setValue($request->getMobileUID());
        $this->redirectIf($memberConfig->save(), 'member/configUID');
      }
    }

    return sfView::SUCCESS;
  }
}
