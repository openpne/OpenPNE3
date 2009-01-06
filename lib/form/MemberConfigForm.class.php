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
 * MemberConfig form.
 *
 * @package    form
 * @subpackage member_config
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class MemberConfigForm extends OpenPNEFormAutoGenerate
{
  protected $memberConfigSettings = array();
  protected $category = '';
  protected $member;
  protected $isNew = false;
  protected $isAutoGenerate = true;

  public function __construct(Member $member = null, $options = array(), $CSRFSecret = null)
  {
    $this->setMemberConfigSettings();

    $this->member = $member;
    if (is_null($this->member)) {
      $this->isNew = true;
      $this->member = new Member();
      $this->member->setIsActive(false);
    } elseif (!$this->member->getIsActive()) {
      $this->isNew = true;
    }

    parent::__construct(array(), $options, $CSRFSecret);

    if ($this->isAutoGenerate) {
      $this->generateConfigWidgets();
    }

    $this->widgetSchema->setNameFormat('member_config[%s]');
  }

  public function generateConfigWidgets()
  {
    foreach ($this->memberConfigSettings as $key => $value) {
      if ($this->isNew && $value['IsRegist'] || !$this->isNew && $value['IsConfig']) {
        $this->setMemberConfigWidget($key);
      }
    }
  }

  public function setMemberConfigSettings()
  {
    $categories = sfConfig::get('openpne_member_category');
    $configs = sfConfig::get('openpne_member_config');

    if (!$this->category) {
      $this->memberConfigSettings = $configs;
      return true;
    }

    foreach ($categories[$this->category] as $value)
    {
      $this->memberConfigSettings[$value] = $configs[$value];
    }
  }

  public function setMemberConfigWidget($name)
  {
    $config = $this->memberConfigSettings[$name];
    $this->widgetSchema[$name] = $this->generateWidget($config);
    $this->widgetSchema->setLabel($name, $config['Caption']);
    $memberConfig = MemberConfigPeer::retrieveByNameAndMemberId($name, $this->member->getId());
    if ($memberConfig) {
      $this->setDefault($name, $memberConfig->getValue());
    }
    $this->validatorSchema[$name] = $this->generateValidator($config);

    if (!empty($config['IsConfirm'])) {
      $this->validatorSchema[$name.'_confirm'] = $this->validatorSchema[$name];
      $this->widgetSchema[$name.'_confirm'] = $this->widgetSchema[$name];
      $this->widgetSchema->setLabel($name.'_confirm', $config['Caption'].'(確認)');

      $this->mergePostValidator(new sfValidatorSchemaCompare($name, '==', $name.'_confirm'));
    }

    if (!empty($config['IsUnique'])) {
      $this->mergePostValidator(new sfValidatorCallback(array(
        'callback' => array($this, 'isUnique'),
        'arguments' => array('name' => $name),
      )));
    }
  }

  public function isUnique($validator, $value, $arguments = array())
  {
    if (empty($arguments['name'])) {
      throw new InvalidArgumentException('Invalid argument');
    }

    $name = $arguments['name'];
    $data = MemberConfigPeer::retrieveByNameAndValue($name, $value[$name]);
    if (!$data || !$data->getMember()->getIsActive() || $data->getMember()->getId() == $this->member->getId()) {
      return $value;
    }

    throw new sfValidatorError($validator, 'This '.$name.' address already exists.');
  }

  public function isValid()
  {
    opActivateBehavior::disable();

    foreach ($this->getValues() as $key => $value)
    {
      if (!empty($this->memberConfigSettings[$key]['IsUnique']))
      {
        $memberConfig = MemberConfigPeer::retrieveByNameAndValue($key.'_pre', $value);
        if ($memberConfig)
        {
          $member = $memberConfig->getMember();
          if (!$member->getIsActive())
          {
            $this->member = $member;
          }
        }
      }
    }

    opActivateBehavior::enable();
    return parent::isValid();
  }

  public function save()
  {
    foreach ($this->getValues() as $key => $value)
    {
      if (strrpos($key, '_confirm'))
      {
        continue;
      }

      $this->saveConfig($key, $value);
    }

    return true;
  }

  public function saveConfig($name, $value)
  {
    $memberConfig = MemberConfigPeer::retrieveByNameAndMemberId($name, $this->member->getId());
    if (!$memberConfig) {
      $memberConfig = new MemberConfig();
      $memberConfig->setName($name);
      $memberConfig->setMember($this->member);
    }
    $memberConfig->setValue($value);

    $memberConfig->save();
  }

  public function savePreConfig($name, $value)
  {
    $memberConfig = MemberConfigPeer::retrieveByNameAndMemberId($name.'_pre', $this->member->getId());
    if (!$memberConfig) {
      $memberConfig = new MemberConfig();
      $memberConfig->setName($name);
      $memberConfig->setMember($this->member);
    }

    $memberConfig->setValue($value);
    $memberConfig->savePre();
    $memberConfig->saveToken();
  }
}
