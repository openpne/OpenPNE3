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
    $this->information = SnsConfigPeer::retrieveByName('pc_home_information');
    return parent::executeHome($request);
  }

 /**
  * Executes list action
  *
  * @param sfRequest $request A request object
  */
  public function executeList($request)
  {
    $this->pager = new sfPropelPager('Member', 20);
    $this->pager->setPage($request->getParameter('page', 1));
    $this->pager->init();

    return sfView::SUCCESS;
  }

 /**
  * Executes profile action
  *
  * @param sfRequest $request A request object
  */
  public function executeProfile($request)
  {
    if ($request->hasParameter('id') && $request->getParameter('id') != $this->getUser()->getMemberId())
    {
      sfConfig::set('sf_navi_type', 'friend');
    }

    return parent::executeProfile($request);
  }

 /**
  * Executes config action
  *
  * @param sfRequest $request A request object
  */
  public function executeConfig($request)
  {
    $this->forms = array();
    $categories = sfConfig::get('openpne_member_category');

    foreach ($categories as $category => $config) {
      $formClass = 'MemberConfig'.ucfirst($category).'Form';
      $this->forms[$category] = new $formClass($this->getUser()->getMember());
    }

    if ($request->isMethod('post')) {
      $targetForm = $this->forms[$request->getParameter('category')];
      $targetForm->bind($request->getParameter('member_config'));
      if ($targetForm->isValid()) {
        $targetForm->save($this->getUser()->getMemberId());
        $this->redirect('member/config');
      }
    }

    return sfView::SUCCESS;
  }

 /**
  * Executes config complete action
  *
  * @param sfRequest $request A request object
  */
  public function executeConfigComplete($request)
  {
    $type = $request->getParameter('type');
    $this->forward404Unless($type);

    $memberId = $request->getParameter('id');

    $memberConfig = MemberConfigPeer::retrieveByNameAndMemberId($type.'_token', $memberId);
    $this->forward404Unless($memberConfig);
    $this->forward404Unless((bool)$request->getParameter('token') !== $memberConfig->getValue());

    $option = array('member' => $memberConfig->getMember());
    $this->form = new sfOpenPNEPasswordForm(array(), $option);

    if ($request->isMethod('post')) {
      $this->form->bind($request->getParameter('password'));
      if ($this->form->isValid()) {
        $config = MemberConfigPeer::retrieveByNameAndMemberId($type, $memberId);
        $pre = MemberConfigPeer::retrieveByNameAndMemberId($type.'_pre', $memberId);

        if (!$config) {
          $config = new MemberConfig();
          $config->setName($type);
        }

        $config->setValue($pre->getValue());

        if ($config->save()) {
          $pre->delete();
          $token = MemberConfigPeer::retrieveByNameAndMemberId($type.'_token', $memberId);
          $token->delete();
        }

        $this->redirect('member/home');
      }
    }

    return sfView::SUCCESS;
  }

 /**
  * Executes invite action
  *
  * @param sfRequest $request A request object
  */
  public function executeInvite($request)
  {
    $this->form = new InviteForm();
    if ($request->isMethod('post'))
    {
      $this->form->bind($request->getParameter('member_config'));
      if ($this->form->isValid())
      {
        $this->form->save();

        $mail = new sfOpenPNEMailSend();
        $mail->setSubject(OpenPNEConfig::get('sns_name').'の招待状が届いています');
        $mail->setTemplate('global/requestRegisterURLMail', array('token' => $this->form->getToken()));
        $mail->send($this->form->getMailAddress(), OpenPNEConfig::get('admin_mail_address'));

        return sfView::SUCCESS;
      }
    }

    return sfView::INPUT;
  }
}
