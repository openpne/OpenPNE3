<?php

/**
 * pcAddress actions.
 *
 * @package    OpenPNE
 * @subpackage pcAddress
 * @author     Kousuke Ebihara <ebihara@tejimaya.net>
 * @version    SVN: $Id: actions.class.php 9301 2008-05-27 01:08:46Z dwhittle $
 */
class pcAddressActions extends sfActions
{
  public function executeRequestRegisterURL($request)
  {
    $this->form = new PCAddressForm();

    if ($request->isMethod('post')) {
      $params = $request->getParameter('pc_address');
      $hash = $this->createHash();
      $this->form->bind($params);

      if ($this->form->isValid() && $this->getUser()->getAuthContainer()->registerEmailAddress($params['pc_address'], $hash)) {
        $subject = OpenPNEConfig::get('sns_name') . 'の招待状が届いています';
        $this->sendMail($subject, 'requestRegisterURLMail', $params['pc_address'], 'kousuke@co3k.org', array('hash' => $hash));

        return sfView::SUCCESS;
      }
    }

    return sfView::INPUT;
  }

  public function executeRegister($request)
  {
    $hash = $request->getParameter('hash');
    $authPCAddress = AuthenticationPcAddressPeer::retrieveByRegisterSession($hash);
    $this->forward404Unless($authPCAddress, 'This URL is invalid.');

    $this->getUser()->setMemberId($authPCAddress->getMemberId());
    $this->getUser()->setIsSNSRegisterBegin(true);

    $this->redirect('member/registerInput');
  }

  public function executeRegisterEnd($request)
  {
    $member = $this->getUser()->getMember();
    $member->setIsActive(true);
    $member->save();

    $authPCAddress = AuthenticationPcAddressPeer::retrieveByMemberId($member->getId());
    $authPCAddress->setRegisterSession('');
    $authPCAddress->save();

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

  private function createHash()
  {
    return md5(uniqid(mt_rand(), true));
  }
}
