<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opAuthLoginFormMailAddress represents a form to login by one's E-mail address.
 *
 * @package    OpenPNE
 * @subpackage form
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opAuthLoginFormMailAddress extends opAuthLoginForm
{
  public function configure()
  {
    $this->setWidgets(array(
      'mail_address' => new sfWidgetFormInput(),
      'password' => new sfWidgetFormInputPassword(),
    ));

    $this->setValidatorSchema(new sfValidatorSchema(array(
      'mail_address' => new sfValidatorEmail(),
      'password' => new sfValidatorString(),
    )));

    $this->mergePostValidator(new sfValidatorOr(array(
      new opAuthValidatorMemberConfigAndPassword(array('config_name' => 'mobile_address', 'field_name' => 'mail_address')),
      new opAuthValidatorMemberConfigAndPassword(array('config_name' => 'pc_address', 'field_name' => 'mail_address')),
    )));

    parent::configure();
  }
}
