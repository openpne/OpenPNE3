<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opAuthRegisterFormMailAddress represents a form to register by E-mail Address.
 *
 * @package    OpenPNE
 * @subpackage form
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opAuthRegisterFormMailAddress extends opAuthRegisterForm
{
  public function configure()
  {
    parent::configure();
    $this->configForm->setMemberConfigWidget('secret_question');
    $this->configForm->setMemberConfigWidget('secret_answer');

    // Hack for non-rendering secret answer
    $this->configForm->getWidget('secret_answer')->setOption('type', 'text');
    
    $this->mergePostValidator(new sfValidatorCallback(array('callback' => array($this, 'validateMemberConfig'))));
  }

  public function bindAll($request)
  {
    // this is just hack for limitation of MemberConfigForm
    if (isset($request['member_config']['secret_answer']))
    {
      $memberConfig = $request['member_config'];
      $memberConfig['secret_answer'] = md5($memberConfig['secret_answer']);
      $request['member_config'] = $memberConfig;
    }

    parent::bindAll($request);
  }

  public function doSave()
  {
    if ($this->getMember()->getConfig('mobile_address') || $this->getMember()->getConfig('pc_address'))
    {
      return true;
    }

    foreach (array('mobile_address', 'pc_address') as $name)
    {
      $memberConfig = Doctrine::getTable('MemberConfig')->retrieveByNameAndMemberId($name.'_pre', $this->getMember()->getId());
      if ($memberConfig)
      {
        $memberConfig->setName($name);
        $memberConfig->save();
      }
    }

    return true;
  }

  public function validateMemberConfig($validator, $values, $arguments = array())
  {
    $memberConfigMobile = Doctrine::getTable('MemberConfig')->retrieveByNameAndMemberId('mobile_address_pre', $this->getMember()->getId());
    $memberConfigPc     = Doctrine::getTable('MemberConfig')->retrieveByNameAndMemberId('pc_address_pre', $this->getMember()->getId());

    if (!$memberConfigMobile && !$memberConfigPc)
    {
      throw new sfValidatorError($validator, 'Your e-mail address is not registered.');
    }

    return $values;
  }
}
