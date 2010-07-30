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
    $publicFlags = Doctrine::getTable('Profile')->getPublicFlags();
    if (isset($publicFlags[ProfileTable::PUBLIC_FLAG_FRIEND]))
    {
      $publicFlags[ProfileTable::PUBLIC_FLAG_FRIEND] = 'My Friends';
    }
    $this->setWidgets(array(
      'name' => new sfWidgetFormInputText(),
      'is_public_web' => new sfWidgetFormSelectRadio(array('choices' => array('0' => 'Deny', '1' => 'Allow'))),
      'is_edit_public_flag' => new sfWidgetFormSelectRadio(array('choices' => array('0' => 'Fixed', '1' => 'Allow member to select'))),
      'default_public_flag' => new sfWidgetFormSelect(array('choices' => $publicFlags)),
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

    $this->mergePostValidator(new sfValidatorCallback(array('callback' => array('ProfileForm', 'validateName'))));
    $this->setValidator('default_public_flag', new sfValidatorChoice(array('choices' => array_keys($publicFlags))));
    $this->setValidator('value_min', new sfValidatorPass());
    $this->setValidator('value_max', new sfValidatorPass());
    $this->setValidator('value_type', new sfValidatorString(array('required' => false, 'empty_value' => 'string')));
    $this->setValidator('name', new opValidatorString(array('required' => true, 'trim' => true)));

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

    $this->widgetSchema->setHelp('is_public_web', 'Anyone in the world may view member profiles');
  }

  public function bind($params)
  {
    if ('input' === $params['form_type'] || 'textarea' === $params['form_type'])
    {
      $validatorArgs = array(
        'required' => false,
        'trim' => true,
      );
      $validatorMin = new sfValidatorInteger($validatorArgs);
      $validatorMax = new sfValidatorInteger($validatorArgs);
      if ('integer' !== $params['value_type'])
      {
        $validatorMin->setOption('min', 0);
        $validatorMax->setOption('min', 1);
      }

      $this->setValidator('value_min', $validatorMin);
      $this->setValidator('value_max', $validatorMax);
    }
    elseif ('date' === $params['form_type'])
    {
      $validatorArgs = array(
        'required' => false,
        'trim' => true,
        'date_format' => '/^(?P<year>\d{4})\/(?P<month>\d{1,2})\/(?P<day>\d{1,2})$/',
        'date_output' => 'Y/m/d',
        'date_format_error' => 'YYYY/MM/DD',
      );
      $validatorMin = new opValidatorDate($validatorArgs);
      $validatorMax = new opValidatorDate($validatorArgs);

      $this->setValidator('value_min', $validatorMin);
      $this->setValidator('value_max', $validatorMax);
    }
    elseif ($params['value_min'] || $params['value_max'])
    {
      throw new sfValidatorError($validator, 'invalid');
    }

    $this->validatorSchema->setPostValidator(new sfValidatorCallback(
      array('callback' => array($this, 'compareMinAndMax')),
      array('invalid' => 'Value must be less than or equal to Minimum value.')
    ));

    return parent::bind($params);
  }

  public function compareMinAndMax(sfValidatorBase $validator, $params)
  {
    $value_min = $params['value_min'];
    $value_max = $params['value_max'];
    if (!is_null($value_min) && !is_null($value_max))
    {
      if ('date' !== $params['form_type'])
      {
        $value_min = (int)$value_min;
        $value_max = (int)$value_max;
      }
      if ($value_min > $value_max)
      {
        throw new sfValidatorErrorSchema($validator, array('value_max' => new sfValidatorError($validator, 'invalid')));
      }
    }

    return $params;
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
