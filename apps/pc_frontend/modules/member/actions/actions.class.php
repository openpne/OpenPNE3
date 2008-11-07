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
    $categories = array('security');

    foreach ($categories as $category) {
      $this->forms[$category] = new MemberConfigForm();
      $this->forms[$category]->setConfigWidgets($category, $this->getUser()->getMemberId());
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
  * Executes invite action
  *
  * @param sfRequest $request A request object
  */
  public function executeInvite($request)
  {
    $this->form = new InviteForm();
    if ($request->isMethod('post'))
    {
      $this->form->bind($request->getParameter('pc_address'));
      if ($this->form->isValid())
      {
        $token = $this->form->register();
        $subject = OpenPNEConfig::get('sns_name').'の招待状が届いています';
        $body = $this->getPartial('global/requestRegisterURLMail', array('token' => $token->getValue()));
        sfOpenPNEMailSend::execute($subject, $this->form->getValue('pc_address'), OpenPNEConfig::get('admin_mail_address'), $body);

        return sfView::SUCCESS;
      }
    }

    return sfView::INPUT;
  }
}
