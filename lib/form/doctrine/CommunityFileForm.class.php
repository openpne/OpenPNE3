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

class CommunityFileForm extends BaseForm
{
  protected
    $community;

  public function __construct($defaults = array(), $options = array(), $CSRFSecret = null)
  {
    return parent::__construct($defaults, $options, false);
  }

  public function configure()
  {
    $this->setCommunity($this->getOption('community'));

    $options = array(
      'file_src'     => '',
      'is_image'     => true,
      'with_delete'  => true,
      'delete_label' => sfContext::getInstance()->getI18N()->__('Remove the current photo')
    );

    if (!$this->community->isNew() && $this->community->getFileId())
    {
      sfContext::getInstance()->getConfiguration()->loadHelpers('Partial');
      $options['edit_mode'] = true;
      $options['template'] = get_partial('default/formEditImage', array('image' => $this->community));
      $this->setValidator('file_delete', new sfValidatorBoolean(array('required' => false)));
    }
    else
    {
      $options['edit_mode'] = false;
    }

    $this->setWidget('file', new sfWidgetFormInputFileEditable($options, array('size' => 40)));
    $this->setValidator('file', new opValidatorImageFile(array('required' => false)));

    $this->widgetSchema->setLabel('file', 'Photo');

    $this->widgetSchema->setNameFormat('community_file[%s]');
  }

  public function setCommunity($community)
  {
    if (!($community instanceof Community))
    {
      $community = new Community();
    }
    $this->community = $community;
  }

  public function save()
  {
    if ($this->getValue('file'))
    {
      if ($this->community->getFile())
      {
        $this->community->getFile()->delete(); 
      }

      $file = new File();
      $file->setFromValidatedFile($this->getValue('file'));
      $file->setName('c_'.$this->community->getId().'_'.$file->getName());

      $this->community->setFile($file);
    }
    elseif ($this->getValue('file_delete'))
    {
      $this->community->getFile()->delete();
      $this->community->setFile(null);
    }
    else
    {
      return;
    }

    $this->community->save();
  }
}
