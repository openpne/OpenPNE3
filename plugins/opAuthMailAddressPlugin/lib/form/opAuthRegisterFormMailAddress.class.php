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

    if (sfConfig::get('app_is_mobile', false))
    {
      $memberConfig = Doctrine::getTable('MemberConfig')->retrieveByNameAndMemberId('mobile_address_pre', $this->getMember()->getId());
      $memberConfig->setName('mobile_address');
    }
    else
    {
      $memberConfig = Doctrine::getTable('MemberConfig')->retrieveByNameAndMemberId('pc_address_pre', $this->getMember()->getId());
      $memberConfig->setName('pc_address');
    }

    $memberConfig->save();

    return $memberConfig;
  }
  
  public function validateMemberConfig($validator, $values, $arguments = array())
  {
    if (sfConfig::get('app_is_mobile', false))
    {
      $memberConfig = Doctrine::getTable('MemberConfig')->retrieveByNameAndMemberId('mobile_address_pre', $this->getMember()->getId());
      if (!$memberConfig)
      {
        throw new sfValidatorError($validator, 'Can access this registration URL with pc only.');
      }
    }
    else
    {
      $memberConfig = Doctrine::getTable('MemberConfig')->retrieveByNameAndMemberId('pc_address_pre', $this->getMember()->getId());
      if (!$memberConfig)
      {
        throw new sfValidatorError($validator, 'Can access this registration URL with mobile only.');
      }
    }

    return $values;
  }

}
