<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opMemberProfileSearchForm
 *
 * @package    OpenPNE
 * @subpackage form
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opMemberProfileSearchForm extends sfForm
{
  public function configure()
  {
    $this->disableCSRFProtection();

    $this->setWidget('member][name', new sfWidgetFormInput());
    $this->widgetSchema->setLabel('member][name', 'Nickname');

    foreach (ProfilePeer::retrievesAll() as $profile)
    {
      $choices = $profile->getOptionsArray();
      $this->setWidget('profile]['.$profile->getName(), opFormItemGenerator::generateSearchWidget($profile->toArray(), $choices));
    }

    $this->setValidators(array(
      'member' => new sfValidatorPass(),
      'profile' => new sfValidatorPass(),
    ));

    $this->widgetSchema->setNameFormat('member[%s]');
  }

  public function getCriteria()
  {
    $c = new Criteria();

    $ids = MemberPeer::searchMemberIds($this->getValue('member'));
    $ids = MemberProfilePeer::searchMemberIds($this->getValue('profile'), $ids, $this->getOption('is_check_public_flag', true));

    if ($this->getValue('member') || $this->getValue('profile'))
    {
      $c->add(MemberPeer::ID, $ids, Criteria::IN);
    }

    return $c;
  }
}

