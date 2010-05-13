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

    $isDispOption = array('choices' => array('1' => '表示する', '0' => '表示しない'));
    $this->setWidgets(array(
      'name' => new sfWidgetFormInputText(),
      'is_edit_public_flag' => new sfWidgetFormSelectRadio(array('choices' => array('0' => '固定', '1' => 'メンバー選択'))),
      'default_public_flag' => new sfWidgetFormSelect(array('choices' => Doctrine::getTable('Profile')->getPublicFlags())),
      'is_disp_regist' => new sfWidgetFormSelectRadio($isDispOption),
      'is_disp_config' => new sfWidgetFormSelectRadio($isDispOption),
      'is_disp_search' => new sfWidgetFormSelectRadio($isDispOption),
      'form_type' => new sfWidgetFormSelect(array('choices' => array(
        'input'    => 'テキスト',
        'textarea' => 'テキスト(複数行)',
        'select'   => '単一選択(プルダウン)',
        'radio'    => '単一選択(ラジオボタン)',
        'checkbox' => '複数選択(チェックボックス)',
        'date'     => '日付',
      ))),
      'value_type' => new sfWidgetFormSelect(array('choices' => array(
        'string' => '文字列',
        'integer' => '数値',
        'email' => 'メールアドレス',
        'url' => 'URL',
        'regexp' => '正規表現',
      ))),
      'is_unique' => new sfWidgetFormSelectRadio(array('choices' => array('0' => '重複可', '1' => '重複不可'))),
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
    $this->setValidator('name', new sfValidatorRegex(array('pattern' => '/^\w*[a-z]\w*$/i')));

    $this->widgetSchema->setLabels(array(
      'name' => '識別名',
      'is_required' => '必須',
      'is_edit_public_flag' => '公開設定の選択',
      'default_public_flag' => '公開設定デフォルト値',
      'is_unique' => '重複の可否',
      'form_type' => 'フォームタイプ',
      'value_type' => '入力値タイプ',
      'value_regexp' => '正規表現',
      'value_min' => '最小値',
      'value_max' => '最大値',
      'is_disp_regist' => '新規登録',
      'is_disp_config' => 'プロフィール変更',
      'is_disp_search' => 'メンバー検索'
   ));

    $this->setDefaults($this->getDefaults() + array(
      'is_unique' => '0',
      'is_disp_regist' => '1',
      'is_disp_config' => '1',
      'is_disp_search' => '1',
    ));

    $this->embedI18n(array('ja_JP'));
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
