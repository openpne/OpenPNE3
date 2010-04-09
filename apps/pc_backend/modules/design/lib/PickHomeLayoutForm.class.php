<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * Pick Home Layout Form.
 *
 * @package    OpenPNE
 * @subpackage form
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class PickHomeLayoutForm extends sfForm
{
  public $choices = array();
  protected $layoutName;

  public function configure()
  {
    $gadgetConfigs = Doctrine::getTable('Gadget')->getConfig();

    $layoutName = $this->getOption('layout_name', 'gadget');
    $this->choices = $gadgetConfigs[$layoutName]['layout']['choices'];
    $default = array_search($gadgetConfigs[$layoutName]['layout']['default'], $this->choices);

    if ($layoutName === 'gadget')
    {
      $layoutName = 'home';
    }
    $this->layoutName = $layoutName.'_layout';

    $snsConfig = Doctrine::getTable('SnsConfig')->retrieveByName($this->layoutName);

    if ($snsConfig)
    {
      $default = array_search($snsConfig->getValue(), $this->choices);
    }

    $this->setWidget('layout', new sfWidgetFormSelectPhotoRadio(array(
      'default'      => (int)$default,
      'class'        => 'layoutSelection',
      'choices'      => $this->choices,
      'image_prefix' => 'layout_selection_',
    )));

    $this->setValidator('layout', new sfValidatorChoice(array(
      'choices' => array_keys($this->choices),
    )));
    $this->widgetSchema->setNameFormat('pick_home_layout[%s]');
  }

  public function save()
  {
    if (!$this->isValid())
    {
      return false;
    }

    $snsConfig = Doctrine::getTable('SnsConfig')->retrieveByName($this->layoutName);
    if (!$snsConfig)
    {
      $snsConfig = new SnsConfig();
      $snsConfig->setName($this->layoutName);
    }
    $value = $this->choices[$this->values['layout']];
    $snsConfig->setValue($value);

    return (bool)$snsConfig->save();
  }
}
