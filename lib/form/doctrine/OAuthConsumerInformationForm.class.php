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
    unset($this['created_at'], $this['updated_at'], $this['file_id'], $this['key_string'], $this['secret'], $this['member_id']);

    $this->setWidget('image', new sfWidgetFormInputFile());
    $this->setValidator('image', new opValidatorImageFile(array('required' => false)));

    $apis = opToolkit::retrieveAPIList();
    $this->setWidget('using_apis', new sfWidgetFormSelectMany(array('choices' => $apis), array('size' => 10)));
    $this->setValidator('using_apis', new sfValidatorChoice(array('multiple' => true, 'choices' => array_keys($apis))));
    $this->getWidgetSchema()->setHelp('using_apis', 'Select apis that your application needs.');

    $this->widgetSchema->getFormFormatter()->setTranslationCatalogue('api');
  }

  public function updateObject($values = null)
  {
    if (is_null($values))
    {
      $values = $this->getValues();
    }

    $image = null;
    if (array_key_exists('image', $values))
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
