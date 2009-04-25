<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opAuthValidatorMemberConfigAndPassword
 *
 * @package    OpenPNE
 * @subpackage validator
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opAuthValidatorMemberConfigAndPassword extends opAuthValidatorMemberConfig
{
  /**
   * @see opAuthValidatorMemberConfig
   */
  protected function configure($options = array(), $messages = array())
  {
    parent::configure($options, $messages);
    $this->setMessage('invalid', 'ID or password is not a valid.');
  }

  /**
   * @see opAuthValidatorMemberConfig
   */
  protected function doClean($values)
  {
    opActivateBehavior::disable();
    $values = parent::doClean($values);

    if (empty($values['member']) || !($values['member'] instanceof Member))
    {
      throw new sfValidatorError($this, 'invalid');
      opActivateBehavior::enable();
    }

    $valid_password = Doctrine::getTable('MemberConfig')->retrieveByNameAndMemberId('password', $values['member']->getId())->getValue();
    opActivateBehavior::enable();
    if (md5($values['password']) !== $valid_password)
    {
      throw new sfValidatorError($this, 'invalid');
    }

    return $values;
  }
}
