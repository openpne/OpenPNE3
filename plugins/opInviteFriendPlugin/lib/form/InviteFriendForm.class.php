<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * InviteFriendForm form.
 *
 * @package    OpenPNE
 * @subpackage form
 * @author     Masato Nagasawa <nagasawa@tejimaya.net>
 */
class InviteFriendForm extends sfForm
{
  public static function __($text)
  {
    return sfContext::getInstance()->getI18N()->__($text);
  }

  public function configure()
  {
    $members = $this->getOption('members');

    $checkboxs = array();
    foreach ($members as $member)
    {
      $checkboxs[$member->getId()] = $member->getName();
    }

    $this->setWidgets(
      array(
        'introduce_to' => new sfWidgetFormChoice(
          array(
            'choices' => $checkboxs,
            'multiple' => true,
            'expanded' => true,
          )
        ),
        'message' => new sfWidgetFormTextarea(
          array('default' => sprintf(self::__('Introduce %s.'), $this->getOption('name')))
        )
      )
    );

    $this->widgetSchema->setLabels(
      array(
        'introduce_to' => 'Introduce to',
        'message' => 'Message'
      )
    );

    $this->validatorSchema['message'] = new sfValidatorString(array('required' => true));
    $this->validatorSchema['introduce_to'] = new sfValidatorChoice(array( 'multiple' => true, 'choices' => array_keys($checkboxs)));
    $this->widgetSchema->setNameFormat('invite_mail[%s]');
  }
}
