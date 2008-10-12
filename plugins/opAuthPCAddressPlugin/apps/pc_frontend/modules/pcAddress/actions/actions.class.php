<?php

/**
 * pcAddress actions.
 *
 * @package    OpenPNE
 * @subpackage pcAddress
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 * @version    SVN: $Id: actions.class.php 9301 2008-05-27 01:08:46Z dwhittle $
 */
class pcAddressActions extends sfActions
{
  public function executeRequestRegisterURL($request)
  {
    $this->form = new PCAddressForm();

    if ($request->isMethod('post')) {
      $params = $request->getParameter('pc_address');
      $this->form->bind($params);

      if ($this->form->isValid()) {
        $member = $this->getUser()->getAuthContainer()->registerEmailAddress($params['pc_address']);
        $token = MemberConfigPeer::retrieveByNameAndMemberId('pc_address_token', $member->getId());

        $subject = OpenPNEConfig::get('sns_name') . 'の招待状が届いています';
        $this->sendMail($subject, 'requestRegisterURLMail', $params['pc_address'], OpenPNEConfig::get('admin_mail_address'), array('token' => $token->getValue()));

        return sfView::SUCCESS;
      }
    }

    return sfView::INPUT;
  }

  public function executeRegister($request)
  {
    $token = $request->getParameter('token');
    $memberConfig = MemberConfigPeer::retrieveByNameAndValue('pc_address_token', $token);
    $this->forward404Unless($memberConfig, 'This URL is invalid.');

    $this->getUser()->setMemberId($memberConfig->getMemberId());
    $this->getUser()->setIsSNSRegisterBegin(true);

    $this->redirect('member/registerInput');
  }

  public function executeRegisterEnd($request)
  {
    $member = $this->getUser()->getMember();
    $member->setIsActive(true);
    $member->save();

    $memberConfig = MemberConfigPeer::retrieveByNameAndMemberId('pc_address_token', $member->getId());
    $memberConfig->delete();

    $this->getUser()->setIsSNSMember(true);
    $this->redirect('member/home');
  }

  private function sendMail($subject, $template, $to, $from, $params = array())
  {
    $swift = new Swift(new Swift_Connection_NativeMail());

    $msg = new Swift_Message(
      mb_convert_encoding($subject, 'JIS', 'UTF-8'),
      mb_convert_encoding($this->getPartial($template, $params), 'JIS', 'UTF-8'),
      'text/plain', '7bit', 'iso-2022-jp'
    );
    $msg->headers->setCharset('ISO-2022-JP');

    return $swift->send($msg, $to, $from);
  }
}
