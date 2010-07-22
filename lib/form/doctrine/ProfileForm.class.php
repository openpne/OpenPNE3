<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * Profile form.
 *
 * @package    OpenPNE
 * @subpackage form
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class ProfileForm extends BaseProfileForm
{
  public function configure()
  {
    unset($this['created_at'], $this['updated_at']);

    $i18n = sfContext::getInstance()->getI18n();
    
    $isDispOption = array('choices' => array('1' => $i18n->__('Allow'), '0' => $i18n->__('Deny')));
    $this->setWidgets(array(
      'name' => new sfWidgetFormInput(),
      'is_edit_public_flag' => new sfWidgetFormSelectRadio(array('choices' => array('0' => $i18n->__('Fixed'), '1' => $i18n->__('Allow member to select')))),
      'default_public_flag' => new sfWidgetFormSelect(array('choices' => Doctrine::getTable('Profile')->getPublicFlags())),
      'is_disp_regist' => new sfWidgetFormSelectRadio($isDispOption),
      'is_disp_config' => new sfWidgetFormSelectRadio($isDispOption),
      'is_disp_search' => new sfWidgetFormSelectRadio($isDispOption),
      'form_type' => new sfWidgetFormSelect(array('choices' => array(
        'input'    => $i18n->__('Text'),
        'textarea' => $i18n->__('Paragraph text'),
        'select'   => $i18n->__('Single choice (Dropdown)'),
        'radio'    => $i18n->__('Single choice (Radio)'),
        'checkbox' => $i18n->__('Multiple choices (Checkbox)'),
        'date'     => $i18n->__('Date'),
      ))),
      'value_type' => new sfWidgetFormSelect(array('choices' => array(
        'string' => $i18n->__('String'),
        'integer' => $i18n->__('Number'),
        'email' => $i18n->__('Email'),
        'url' => $i18n->__('URL'),
        'regexp' => $i18n->__('Regular expression'),
      ))),
      'is_unique' => new sfWidgetFormSelectRadio(array('choices' => array('0' => $i18n->__('Allow'), '1' => $i18n->__('Deny')))),
      'sort_order' => new sfWidgetFormInputHidden(),
    ) + $this->getWidgetSchema()->getFields());

    $this->widgetSchema->setNameFormat('profile[%s]');

    $this->validatorSchema->setPostValidator(
      new sfValidatorDoctrineUnique(array('model' => 'Profile', 'column' => array('name')), array('invalid' => 'Already exist.'))
    );

    $this->mergePostValidator(new sfValidatorCallback(array('callback' => array('ProfileForm', 'validateName'))));
    $this->setValidator('default_public_flag', new sfValidatorChoice(array('choices' => array_keys(Doctrine::getTable('Profile')->getPublicFlags()))));
    $this->setValidator('value_min', new sfValidatorPass());
    $this->setValidator('value_max', new sfValidatorPass());
    $this->setValidator('value_type', new sfValidatorString(array('required' => false, 'empty_value' => 'string')));

    $this->widgetSchema->setLabels(array(
      'name' => 'Identification name',
      'is_required' => 'Required',
      'is_edit_public_flag' => 'Public setting',
      'default_public_flag' => 'Public default setting',
      'is_unique' => 'Duplication',
      'form_type' => 'Input type',
      'value_type' => 'Value type',
      'value_regexp' => 'Regular expression',
      'value_min' => 'Minimum',
      'value_max' => 'Maximum',
      'is_disp_regist' => 'New registration',
      'is_disp_config' => 'Change profile',
      'is_disp_search' => 'Member search'
   ));

    $this->setDefaults($this->getDefaults() + array(
      'is_unique' => '0',
      'is_disp_regist' => '1',
      'is_disp_config' => '1',
      'is_disp_search' => '1',
    ));

    $this->embedI18n(sfConfig::get('op_supported_languages'));
  }

  public function bind($params)
  {
    if ($params['form_type'] === 'input' || $params['form_type'] === 'textarea')
    {
      $validator = new sfValidatorInteger(array('required' => false));
      $this->setValidator('value_min', $validator);
      $validator = new sfValidatorInteger(array('required' => false));
      $this->setValidator('value_max', $validator);
    }
    elseif ($params['form_type'] === 'date')
    {
      $validator = new opValidatorDate(array('required' => false));
      $this->setValidator('value_min', $validator);
      $validator = new opValidatorDate(array('required' => false));
      $this->setValidator('value_max', $validator);
    }
    elseif ($params['value_min'] || $params['value_max'])
    {
      throw new sfValidatorError($validator, 'invalid');
    }

    return parent::bind($params);
  }

  static public function validateName($validator, $values)
  {
    if (0 === strpos($values['name'], 'op_preset_'))
    {
      throw new sfValidatorError($validator, 'invalid');
    }

    return $values;
  }

  public function save($con = null)
  {
    $profile  = parent::save($con);

    $values = $this->getValues();

    if (!$values['is_edit_public_flag'])
    {
      Doctrine_Query::create()
        ->update('MemberProfile')
        ->set('public_flag', $values['default_public_flag'])
        ->where('lft = 1')
        ->andWhere('profile_id = ?', $profile->getId())
        ->execute();
    }

    if ($values['form_type'] === 'date')
    {
      if (!$profile->getProfileOption()->count())
      {
        $dateField = array('year', 'month', 'day');
        foreach ($dateField as $k => $field)
        {
          $profileOption = new ProfileOption();
          $profileOption->setSortOrder($k);
          $profileOption->setProfile($profile);
          $profileOption->save();
        }
      }
    }
  }
}
