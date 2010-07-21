<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * ProfileOption form.
 *
 * @package    OpenPNE
 * @subpackage form
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class ProfileOptionForm extends BaseProfileOptionForm
{
  public function configure()
  {
    $options = array();
    if ($this->object && $this->object->getId()) {
      $options['id_format'] = '%s' . $this->object->getId();
    }

    $this->setWidgets(array(
      'id'         => new sfWidgetFormInputHidden(),
      'profile_id' => new sfWidgetFormInputHidden(),
      'sort_order' => new sfWidgetFormInputHidden(),
    ));

    $this->setValidators(array(
      'id'         => new sfValidatorDoctrineChoice(array('model' => 'ProfileOption', 'column' => 'id', 'required' => false)),
      'profile_id' => new sfValidatorDoctrineChoice(array('model' => 'Profile', 'column' => 'id', 'required' => true)),
      'sort_order' => new sfValidatorInteger(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('profile_option[%s]');

    $this->embedI18n(sfConfig::get('op_supported_languages'));
    unset($this['created_at'], $this['updated_at']);
  }

  public function updateObject()
  {
    if (!$this->isNew())
    {
      unset($this->values['sort_order']);
    }

    return parent::updateObject();
  }
}
