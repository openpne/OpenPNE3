<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * ImageForm
 *
 * @package    OpenPNE
 * @subpackage form
 * @author     Shinichi Urabe <urabe@tejimaya.net>
 */
class ImageForm extends BaseFileForm
{
  public function configure()
  {
    $this->setWidgets(array(
      'file'      => new sfWidgetFormInputFile(),
      'imageName' => new sfWidgetFormInputText(),
    ));
    $this->widgetSchema->setLabels(array(
      'file'      => sfContext::getInstance()->getI18N()->__('画像ファイル'),
      'imageName' => sfContext::getInstance()->getI18N()->__('画像ファイル名'),
    ));
    $this->setValidators(array(
      'file'      => new opValidatorImageFile(),
      'imageName' => new sfValidatorString(),
    ));

    $this->widgetSchema->setNameFormat('image[%s]');
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

    $file = new File();
    $file->setFromValidatedFile($this->getValue('file'));
    $file->setName(sprintf('admin_%s', $this->getValue('imageName')));

    return $file->save();
  }
}
