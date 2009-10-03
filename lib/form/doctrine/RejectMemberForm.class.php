<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * RejectMember form.
 *
 * @package    form
 * @subpackage member
 * @author     Shinichi Urabe <urabe@tejimaya.com>
 */
class RejectMemberForm extends BaseMemberForm
{
  protected
    $member;
  public function __construct(Member $defaults)
  {
    $this->member = $defaults;

    return parent::__construct($defaults);
  }

  public function configure()
  {
    $param = $this->member->getIsLoginRejected() ? false : true;
    $this->setWidgets(array(
      'is_login_rejected' => new sfWidgetFormInputHidden(array(), array('value' => $param)),
    ));

    $this->setValidators(array(
      'is_login_rejected' => new sfValidatorBoolean(),
    ));
    $this->widgetSchema->setNameFormat('is_login_rejected[%s]');
  }
}
