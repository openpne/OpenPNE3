<?php

/**
 * PluginMessageFile form.
 *
 * @package    form
 * @subpackage MessageFile
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 6174 2007-11-27 06:22:40Z fabien $
 */
abstract class PluginMessageFileForm extends BaseMessageFileForm
{
  public function setup()
  {
    parent::setup();

    unset($this['message_id']);
    unset($this['file_id']);
    unset($this['created_at'], $this['updated_at']);

    $key = 'image';

    $options = array(
        'file_src'     => '',
        'is_image'     => true,
        'with_delete'  => true,
        'delete_label' => 'remove the current image',
        'label'        => false,
        'edit_mode'    => !$this->isNew(),
        );

    if (!$this->isNew())
    {
      sfContext::getInstance()->getConfiguration()->loadHelpers('Partial');
      $options['template'] = get_partial('message/formEditImage', array('image' => $this->getObject()));
      $this->setValidator($key.'_delete', new sfValidatorBoolean(array('required' => false)));
    }

    $this->setWidget($key, new sfWidgetFormInputFileEditable($options, array('size' => 40)));
    $this->setValidator($key, new opValidatorImageFile(array('required' => false)));
  }

  public function updateObject($values = null)
  {
    if ($values['image'] instanceof sfValidatedFile)
    {
      if (!$this->isNew())
      {
        unset($this->getObject()->File);
      }

      $file = new File();
      $file->setFromValidatedFile($values['image']);

      $this->getObject()->setFile($file);
      return;
    }

    if (!empty($values['image_delete']) && !$this->isNew())
    {
      $this->getObject()->getFile()->delete();
    }
    $this->object = null;
  }

/*
  public function updateObject($values = null)
  {
    $object = parent::updateObject($values);

    foreach ($this->embeddedForms as $key => $form)
    {
      if (!($form->getObject() && $form->getObject()->getFile()))
      {
        unset($this->embeddedForms[$key]);
      }
    }

    return $object;
  }
*/
}
