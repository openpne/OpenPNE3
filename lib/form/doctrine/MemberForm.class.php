<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * Member form.
 *
 * @package    form
 * @subpackage member
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class MemberForm extends BaseMemberForm
{
  public function configure()
  {
    $this->setWidgets(array(
      'name' => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'name' => new opValidatorString(array('max_length' => 64, 'trim' => true)),
    ));

    $this->widgetSchema->setLabel('name', '%nickname%');
    $this->widgetSchema->setNameFormat('member[%s]');
    $this->widgetSchema->getFormFormatter()->setTranslationCatalogue('form_member');
  }

  protected function doSave($con = null)
  {
    if ($this->isNew()) {
      $this->object->setIsActive(false);
    }

    parent::doSave($con);
  }
}
