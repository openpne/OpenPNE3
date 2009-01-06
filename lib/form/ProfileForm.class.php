<?php

/**
 * Copyright (C) 2005-2009 OpenPNE Project
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *  http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/**
 * Profile form.
 *
 * @package    symfony
 * @subpackage form
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class ProfileForm extends BaseProfileForm
{
  public function configure()
  {
    $isDispOption = array('choices' => array('1' => '表示する', '0' => '表示しない'));

    $this->setWidgets(array(
      'name' => new sfWidgetFormInput(),
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
      new sfValidatorPropelUnique(array('model' => 'Profile', 'column' => array('name')))
    );

    $this->widgetSchema->setLabels(array(
      'name' => '識別名',
      'is_required' => '必須',
      'is_unique' => '重複の可否',
      'form_type' => 'フォームタイプ',
      'value_type' => '入力値タイプ',
      'value_regexp' => '正規表現',
      'value_min' => '最小値',
      'value_max' => '最大値',
      'is_disp_regist' => '新規登録',
      'is_disp_config' => 'プロフィール変更',
      'is_disp_search' => 'メンバー検索',
    ));

    $this->setDefaults($this->getDefaults() + array(
      'is_unique' => '0',
      'is_disp_regist' => '1',
      'is_disp_config' => '1',
      'is_disp_search' => '1',
    ));

    $this->embedI18n(array('ja_JP'));
  }
}
