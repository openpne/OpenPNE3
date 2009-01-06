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
 * MemberImageForm
 *
 * @package    OpenPNE
 * @subpackage form
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class MemberImageForm extends sfForm
{
  public function configure()
  {
    $this->member = $this->getOption('member');
    $this->setWidget('file', new sfWidgetFormInputFile());
    $this->setValidator('file', new opValidatorImageFile());
    $this->widgetSchema->setNameFormat('member_image[%s]');
  }

  public function bindAndSave(array $taintedValues = null, array $taintedFiles = null)
  {
    $this->bind($taintedValues, $taintedFiles);
    if ($this->isValid())
    {
      return $this->save();
    }
    return false;
  }

  public function save()
  {
    $count = $this->member->countMemberImages();
    if ($count >= 3)
    {
      throw new sfException('Cannot add an image any more.');
    }

    $file = new File();
    $file->setFromValidatedFile($this->getValue('file'));
    $file->setName('m_'.$this->member->getId().'_'.$file->getName());

    $memberImage = new MemberImage();
    $memberImage->setMember($this->member);
    $memberImage->setFile($file);
    if (!$count)
    {
      $memberImage->setIsPrimary(true);
    }
    $memberImage->save();
  }
}
