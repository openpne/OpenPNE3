<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * ApplicationSettingForm
 *
 * @package    opOpenSocialPlugin
 * @subpackage form
 * @author     Shogo Kawahara <kawahara@tejimaya.net>
 */
class ApplicationSettingForm extends sfForm
{
  static protected
    $publicFlagChoices = array(
      'public'  => 'All Members',
      'friends' => 'My Friends',
      'private' => 'Private', 
    );

  protected
    $memberApplication = null;

  public function setup()
  {
    $this->memberApplication = $this->getOption('member_application', null);
    if (!$this->memberApplication)
    {
      throw new LogicException();
    }

    $this->setWidgets(array(
      'public_flag' => new sfWidgetFormChoice(array('choices' => array_map(array(sfContext::getInstance()->getI18n(), '__'), self::$publicFlagChoices))),
    ));
    $this->setValidators(array(
      'public_flag' => new sfValidatorChoice(array('choices' => array_keys(self::$publicFlagChoices))),
    ));
    
    if (opOpenSocialToolKit::isEnableHomeGadget())
    {
      $this->setWidget('is_view_home', new sfWidgetFormInputCheckbox());
      $this->setValidator('is_view_home', new sfValidatorBoolean());
      $this->widgetSchema->setLabel('is_view_home', 'Display on the home');
    }

    if (opOpenSocialToolKit::isEnableProfileGadget())
    {
      $this->setWidget('is_view_profile', new sfWidgetFormInputCheckbox());
      $this->setValidator('is_view_profile', new sfValidatorBoolean());
      $this->widgetSchema->setLabel('is_view_profile', 'Display on your profile');
    }

    foreach ($this->memberApplication->getApplicationSettings() as $name => $value)
    {
      if (!empty($value))
      {
        $this->setDefault($name, $value);
      }
    }

    $this->setDefault('public_flag', $this->memberApplication->getPublicFlag());

    $this->widgetSchema->setNameFormat('setting[%s]');
  }

  public function save()
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }
    
    $values = $this->getValues();

    $this->memberApplication->setPublicFlag($values['public_flag']);
    $this->memberApplication->save();

    unset($values['public_flag']);

    foreach ($values as $name => $value)
    {
      $this->memberApplication->setApplicationSetting($name, $value);
    }
  }
}

