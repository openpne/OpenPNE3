<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * Community file form.
 *
 * @package    OpenPNE
 * @subpackage form
 * @author     Shogo Kawahara <kawahara@tejimaya.com>
 */

class CommunityFileForm extends sfForm
{
  public function __construct($defaults = array(), $options = array(), $CSRFSecret = null)
  {
    return parent::__construct($defaults, $options, false);
  }

  public function configure()
  {
    $this->setWidget('file', new sfWidgetFormInputFile());
    $this->setValidator('file', new opValidatorImageFile(array('required' => false)));

    $this->widgetSchema->setLabel('file', 'Photo');

    $this->widgetSchema->setNameFormat('community_file[%s]');
  }

  public function save(Community $community)
  {
    if (!$this->getValue('file'))
    {
      return false;
    }

    if ($community->getFile())
    {
      $community->getFile()->delete(); 
    }

    $file = new File();
    $file->setFromValidatedFile($this->getValue('file'));
    $file->setName('c_'.$community->getId().'_'.$file->getName());

    $community->setFile($file);

    $community->save();
  }
}
