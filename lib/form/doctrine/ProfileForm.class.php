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

    $this->widgetSchema->getFormFormatter()->setTranslationCatalogue('profile_form');
    
    $isDispOption = array('choices' => array('1' => 'Allow', '0' => 'Deny'));
    $this->setWidgets(array(
      'name' => new sfWidgetFormInputText(),
      'is_public_web' => new sfWidgetFormSelectRadio(array('choices' => array('0' => 'Deny', '1' => 'Allow'))),
      'is_edit_public_flag' => new sfWidgetFormSelectRadio(array('choices' => array('0' => 'Fixed', '1' => 'Allow member to select'))),
      'default_public_flag' => new sfWidgetFormSelect(array('choices' => Doctrine::getTable('Profile')->getPublicFlags(false))),
      'is_disp_regist' => new sfWidgetFormSelectRadio($isDispOption),
      'is_disp_config' => new sfWidgetFormSelectRadio($isDispOption),
      'is_disp_search' => new sfWidgetFormSelectRadio($isDispOption),
      'form_type' => new sfWidgetFormSelect(array('choices' => array(
        'input'    => 'Text',
        'textarea' => 'Paragraph text',
        'select'   => 'Single choice (Dropdown)',
        'radio'    => 'Single choice (Radio)',
        'checkbox' => 'Multiple choices (Checkbox)',
        'date'     => 'Date',
      ))),
      'value_type' => new sfWidgetFormSelect(array('choices' => array(
        'string' => 'String',
        'integer' => 'Number',
        'email' => 'Email',
        'url' => 'URL',
        'regexp' => 'Regular expression',
      ))),
      'is_unique' => new sfWidgetFormSelectRadio(array('choices' => array('0' => 'Allow', '1' => 'Deny'))),
      'sort_order' => new sfWidgetFormInputHidden(),
    ) + $this->getWidgetSchema()->getFields());

    $this->widgetSchema->setNameFormat('profile[%s]');

    $this->validatorSchema->setPostValidator(
      new sfValidatorDoctrineUnique(array('model' => 'Profile', 'column' => array('name')), array('invalid' => 'Already exist.'))
    );

    $this->mergePostValidator(new sfValidatorCallback(
      array('callback' => array('ProfileForm', 'validateName')),
      array('invalid' => 'Can not use "op_preset_" as a prefix.')
    ));
    $this->mergePostValidator(new sfValidatorCallback(array('callback' => array('ProfileForm', 'validateValueMin'))));
    $this->mergePostValidator(new sfValidatorCallback(array('callback' => array('ProfileForm', 'validateValueMax'))));

    $this->setValidator('default_public_flag', new sfValidatorChoice(array('choices' => array_keys(Doctrine::getTable('Profile')->getPublicFlags(false)))));
    $this->setValidator('value_min', new sfValidatorPass());
    $this->setValidator('value_max', new sfValidatorPass());
    $this->setValidator('value_type', new sfValidatorString(array('required' => false, 'empty_value' => 'string')));
    $this->setValidator('name', new sfValidatorRegex(
      array('pattern' => '/^\w*[a-z]\w*$/i'),
      array('invalid' => 'Format is invalid.')
    ));

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
      'is_disp_search' => 'Member search',
      'is_public_web' => 'Make it public',
   ));

    $this->setDefaults($this->getDefaults() + array(
      'is_unique' => '0',
      'is_disp_regist' => '1',
      'is_disp_config' => '1',
      'is_disp_search' => '1',
    ));

    $this->embedI18n(sfConfig::get('op_supported_languages'));

    $this->widgetSchema->setHelps(array(
      'name' => 'Identification name can contain only underscore or alphanumeric characters. (must have at least one alphabet.)',
      'is_public_web' => 'Anyone in the world may view member profiles',
      'is_unique' => 'This setting is applied only for Input type "Text" or "Paragraph text".',
    ));
  }

  static public function validateValue($validator, $values, $valueKey)
  {
    $options = array('required' => false);
    $validator = null;

    switch ($values['form_type'])
    {
      case 'input':
      case 'textarea':
        $validator = new sfValidatorInteger($options);
        break;
      case 'date':
        $options['date_format_range_error'] = 'Y-m-d';
        $validator = new opValidatorDate($options);
        break;
      default:
        break; // Do nothing.
    }

    if (null !== $validator)
    {
      try
      {
        $cleanValue = $validator->clean($values[$valueKey]);

        if (!($validator instanceof opValidatorDate))
        {
          $values[$valueKey] = $cleanValue;
        }
      }
      catch (Exception $e)
      {
        throw new sfValidatorErrorSchema($validator, array($valueKey => new sfValidatorError($validator, 'invalid')));
      }
    }
    elseif ($values[$valueKey])
    {
      throw new sfValidatorError($validator, 'invalid');
    }

    return $values;
  }

  static public function validateValueMin($validator, $values)
  {
    return self::validateValue($validator, $values, 'value_min');
  }

  static public function validateValueMax($validator, $values)
  {
    return self::validateValue($validator, $values, 'value_max');
  }

  static public function validateName($validator, $values)
  {
    if (0 === strpos($values['name'], 'op_preset_'))
    {
      throw new sfValidatorErrorSchema($validator, array('name' => new sfValidatorError($validator, 'invalid')));
    }

    return $values;
  }

  public function save($con = null)
  {
    $profile  = parent::save($con);

    $values = $this->getValues();

    if ('date' === $values['form_type'])
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
