<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class opSkinClassicImageForm extends sfForm
{
  public function configure()
  {
    $target = $this->getOption('target');

    $options = array(
      'file_src'    => '',
      'is_image'    => true,
      'with_delete' => false,
      'label'       => sfInflector::humanize($target),
    );

    sfContext::getInstance()->getConfiguration()->loadHelpers('Partial');
    $options['template'] = get_partial('opSkinClassicPlugin/formEditImage', array('target' => $target));

    $this->setWidget('image', new sfWidgetFormInputFileEditable($options, array('size' => 10)));
    $this->setValidator('image', new opValidatorImageFile(array('required' => true)));

    $this->widgetSchema->setNameFormat('image['.$target.'][%s]');
  }

  public function save()
  {
    $target = $this->getOption('target');

    $rawConfig = Doctrine::getTable('SkinConfig')->retrieveByPluginAndName('opSkinClassicPlugin', $target.'_image');
    if ($rawConfig)
    {
      $file = Doctrine::getTable('File')->findOneByName($rawConfig->value);
      if ($file)
      {
        $file->delete();
      }
    }

    $file = new File();
    $file->setFromValidatedFile($this->getValue('image'));
    $file->save();

    opSkinClassicConfig::set($target.'_image', $file->name);

    opToolkit::clearCache();
  }
}
