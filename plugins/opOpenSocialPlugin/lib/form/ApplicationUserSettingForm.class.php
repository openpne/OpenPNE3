<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * ApplicationUserSettingForm
 *
 * @package    opOpenSocialPlugin
 * @subpackage form
 * @author     Shogo Kawahara <kawahara@tejimaya.net>
 */
class ApplicationUserSettingForm extends sfForm
{
  protected
    $memberApplication = null;

  public function setup()
  {
    $this->memberApplication = $this->getOption('member_application', null);
    if (!$this->memberApplication)
    {
      throw new LogicException();
    }
    $this->setConfigWidget();

    $this->widgetSchema->setNameFormat('user_setting[%s]');
  }

  protected function setConfigWidget()
  {
    sfContext::getInstance()->getConfiguration()->loadHelpers(array('Escaping'));

    $application = $this->memberApplication->getApplication();
    $settings = $application->getSettings();
    foreach ($settings as $key => $setting)
    {
      $param   = array();
      $choices = array();
      $param['IsRequired'] = false;
      $param['Caption'] = sfOutputEscaper::escape(sfConfig::get('sf_escaping_method'), $setting['displayName']);
      if (empty($setting['datatype']) || $setting['datatype'] == 'HIDDEN')
      {
        continue;
      }
      switch ($setting['datatype'])
      {
        case 'BOOL' :
          $param['FormType'] = 'checkbox';
          $choices = array('1' => '');
          break;
        case 'ENUM' :
          $param['FormType'] = 'select';
          $enumValues = array();
          if (!is_array($setting['enumValues']))
          {
            continue;
          }
          foreach ($setting['enumValues'] as $value)
          {
            $enumValues[$value['value']] = $value['displayValue'];
          }
          $choices = $enumValues;
          break;
        default :
          $param['FormType'] = 'input';
          $param['ValueType'] = '';
      }
      $this->widgetSchema[$key]    = opFormItemGenerator::generateWidget($param, $choices);
      $this->validatorSchema[$key] = opFormItemGenerator::generateValidator($param, array_keys($choices));

      if ($setting['defaultValue'])
      {
        $this->setDefault($key, $setting['defaultValue']);
      }

    }
    $userSettings = $this->memberApplication->getUserSettings();

    foreach ($userSettings as $name => $value)
    {
      if (!empty($value))
      {
        $this->setDefault($name, $value);
      }
    }
  }

  public function save()
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    foreach ($this->getValues() as $name => $value)
    {
      $this->memberApplication->setUserSetting($name, $value);
    }
  }
}

