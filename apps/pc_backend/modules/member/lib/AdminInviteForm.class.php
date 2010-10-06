<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * AdminInvite form.
 *
 * @package    OpenPNE
 * @subpackage form
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class AdminInviteForm extends InviteForm
{
  public function configure()
  {
    $choice = $this->getOption('authModes', array());
    $this->setWidget('auth_mode', new sfWidgetFormChoice(array('choices' => $choice)));
    $this->setValidator('auth_mode', new sfValidatorChoice(array('choices' => array_keys($choice))));

    $this->setWidget('mail_address', new sfWidgetFormTextarea());
    $this->setValidator('mail_address', new sfValidatorPass());

    $this->validatorSchema->setPostValidator(new sfValidatorCallback(array(
        'callback' => array($this, 'validate'),
    )));
  }

  public function validate($validator, $values, $arguments = array())
  {
    $values = $values + array('pc' => array(), 'mobile' => array(), 'invalid' => array());

    $inputList = explode("\n", opToolkit::unifyEOLCharacter($values['mail_address']));
    $inputList = array_unique(array_map('trim', $inputList));
    foreach ($inputList as $value)
    {
      try
      {
        $result = parent::validate($validator, array('mail_address' => $value));
        if (!empty($result['pc_address']))
        {
          $values['pc'][] = $result['pc_address'];
        }
        elseif (!empty($result['mobile_address']))
        {
          $values['mobile'][] = $result['mobile_address'];
        }
      }
      catch (sfValidatorError $e)
      {
        $values['invalid'][] = $value;
      }
    }

    if (empty($values['pc']) && empty($values['mobile']))
    {
      throw new sfValidatorError($validator, 'All of the inputted E-mail addresses are invalid.');
    }

    return $values;
  }

  public function save()
  {
    $authModes = $this->getOption('authModes', array());
    $authMode = $authModes[$this->getValue('auth_mode')];
    $this->setOption('authMode', $authMode);

    foreach ($this->getValue('pc') as $value)
    {
      $this->member = Doctrine::getTable('Member')->createPre();
      $this->saveConfig('pc_address', $value);
      $this->member->setConfig('register_auth_mode', $authMode);
      $this->member->setConfig('is_admin_invited', true);
    }

    foreach ($this->getValue('mobile') as $value)
    {
      $this->member = Doctrine::getTable('Member')->createPre();
      $this->saveConfig('mobile_address', $value);
      $this->member->setConfig('register_auth_mode', $authMode);
      $this->member->setConfig('is_admin_invited', true);
    }

    return true;
  }
}
