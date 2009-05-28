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

  public function save($con = null)
  {
    parent::save($con);

    if ($this->getValue('image'))
    {
      $file = new File();
      $file->setFromValidatedFile($this->getValue('image'));
      $file->setName('oauth_'.$this->getObject()->getId().'_'.$file->getName());
      $this->getObject()->setImage($file);
      $this->getObject()->save();
    }
  }

  protected function processUploadedFile($field, $filename = null, $values = null)
  {
    return '';
  }
}
