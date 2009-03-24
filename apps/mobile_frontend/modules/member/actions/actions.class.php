<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

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
    $this->gadgetConfig = sfConfig::get('op_mobile_gadget_list');
    $this->mobileTopGadgets = GadgetPeer::retrieveMobileTopGadgets();
    $this->mobileContentsGadgets = GadgetPeer::retrieveMobileContentsGadgets();
    $this->mobileBottomGadgets = GadgetPeer::retrieveMobileBottomGadgets();

    $this->categories = sfConfig::get('openpne_member_category');
    $this->categoryCaptions = array();
    $attributes = sfConfig::get('openpne_member_category_attribute');
    foreach ($this->categories as $key => $value)
    {
      $title = $key;
      
      if (isset($attributes[$key]['enable_mobile']))
      {
        if (!$attributes[$key]['enable_mobile'])
        {
          unset($this->categories[$key]);
          continue;
        }
      }

      if (!empty($attributes[$key]['caption']))
      {
        $title = $attributes[$key]['caption'];
      }

      $this->categoryCaptions[$key] = $title;
    }
    

    return parent::executeHome($request);
  }

 /**
  * Executes profile action
  *
  * @params sfRequest $request A request object
  */ 
  public function executeProfile($request)
  {
    $this->friendsSize = 5;
    $this->communitiesSize = 5;

    return parent::executeProfile($request);
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

 /**
  * Executes registerMobileToRegisterEnd action
  *
  * @param sfRequest $request A request object
  */
  public function executeRegisterMobileToRegisterEnd(sfWebRequest $request)
  {

    $id = $request->getParameter('id');
    $token = $request->getParameter('token');

    $memberConfig = MemberConfigPeer::retrieveByNameAndMemberId('register_mobile_token', $id);

    $this->forward404Unless($memberConfig && $token === $memberConfig->getValue());

    opActivateBehavior::disable();
    $this->form = new sfOpenPNEPasswordForm(null, array('member' => $memberConfig->getMember()));
    opActivateBehavior::enable();

    if ($request->isMethod(sfWebRequest::POST))
    {
      $this->form->bind($request->getParameter('password'));
      if ($this->form->isValid())
      {
        $memberConfig->getMember()->setConfig('mobile_uid', $request->getMobileUID());

        $this->getUser()->setCurrentAuthMode($memberConfig->getMember()->getConfig('register_auth_mode'));
        $this->getUser()->setMemberId($id);
        $this->redirect($this->getUser()->getRegisterEndAction());
      }
    }

    return sfView::SUCCESS;
  }
}
