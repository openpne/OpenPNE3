<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * OAuthConsumerInformation form.
 *
 * @package    OpenPNE
 * @subpackage form
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class OAuthConsumerInformationForm extends BaseOAuthConsumerInformationForm
{
  public function configure()
  {
    unset($this['created_at'], $this['updated_at'], $this['file_id'], $this['key_string'], $this['secret']);

    $this->setWidget('type', new sfWidgetFormSelectRadio(array('choices' => array('browser' => 'Web Application', 'client' => 'Desktop Application'))));
    $this->widgetSchema->setLabel('type', 'Application Type');

    $this->setWidget('image', new sfWidgetFormInputFile());
    $this->setValidator('image', new opValidatorImageFile(array('required' => false)));
  }

  public function updateObject($values = null)
  {
    if (is_null($values))
    {
      $values = $this->getValues();
    }

    $image = null;
    if (isset($values['image']))
    {
      $image = $values['image'];
      unset($values['image']);
    }

    $obj = parent::updateObject($values);

    if ($image instanceof sfValidatedFile)
    {
      unset($obj->Image);

      $file = new File();
      $file->setFromValidatedFile($image);
      $file->setName('oauth_'.$obj->getId().'_'.$file->getName());
      $obj->setImage($file);
    }
  }

  protected function processUploadedFile($field, $filename = null, $values = null)
  {
    return '';
  }
}
