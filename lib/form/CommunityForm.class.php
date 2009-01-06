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
 * Community form.
 *
 * @package    OpenPNE
 * @subpackage form
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class CommunityForm extends BaseCommunityForm
{
  protected $configForm;

  public function configure()
  {
    unset($this['created_at'], $this['updated_at'], $this['file_id'], $this['id']);

    $this->setValidator('name', new sfValidatorString(array('max_length' => 64, 'trim' => true)));

    $this->setConfigForm();

    $this->setWidget('file', new sfWidgetFormInputFile());
    $this->setValidator('file', new opValidatorImageFile(array('required' => false)));
  }

  public function save($con = null)
  {
    $community = parent::save($con);
    $oldFile = $community->getFile();
    $this->saveImageFile($community);
    $community->save();

    if ($oldFile && $oldFile->getName() !== $community->getFile()->getName())
    {
      $oldFile->delete();
    }

    return $community;
  }

  public function updateObject($values = null)
  {
    $object = parent::updateObject($values);

    $this->saveMember($object);
    if ($this->configForm->isValid())
    {
      $this->configForm->save();
    }

    return $object;
  }

  public function bind(array $taintedValues = null, array $taintedFiles = null)
  {
    parent::bind($taintedValues, $taintedFiles);

    $configs = $this->getValue('config');
    if (!$configs)
    {
      $configs = array();
    }

    $params = array();
    foreach ($configs as $key => $value)
    {
      $params['config['.$key.']'] = $value;
    }

    $this->configForm->bind($params);
    foreach ($this->configForm->getErrorSchema() as $key => $value)
    {
      $this->getErrorSchema()->addError($value, $key);
    }
  }

  public function setConfigForm()
  {
    $this->configForm = new CommunityConfigForm(array(), array('community' => $this->getObject()));
    $this->mergeForm($this->configForm);
    foreach ($this->configForm->getValidatorSchema()->getFields() as $field => $validator)
    {
      $this->validatorSchema[$field] = new sfValidatorPass();
    }
    $this->validatorSchema['config'] = new sfValidatorPass();
  }

  public function saveMember(Community $community)
  {
    if ($this->isNew())
    {
      $member = new CommunityMember();
      $member->setPosition('admin');
      $member->setMemberId(sfContext::getInstance()->getUser()->getMemberId());
      $member->setCommunity($community);
    }
  }

  public function saveImageFile(Community $community)
  {
    if (!$this->getValue('file'))
    {
      return false;
    }

    $file = new File();
    $file->setFromValidatedFile($this->getValue('file'));
    $file->setName('c_'.$community->getId().'_'.$file->getName());
    $community->setFile($file);

    return true;
  }
}
