<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opSnsTermForm
 *
 * @package    OpenPNE
 * @subpackage form
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opSnsTermForm extends sfForm
{
  protected static $availableApplications = array(
    'pc_frontend' => 'pc',
    'mobile_frontend' => 'mobile',
  );

  public function configure()
  {
    $culture = sfContext::getInstance()->getUser()->getCulture();

    foreach (self::getAvailableTerms() as $name => $config)
    {
      foreach (self::$availableApplications as $app => $appCaption)
      {
        $field = $name.'['.$app.']';
        $this->setWidget($field, new sfWidgetFormInput());
        $this->widgetSchema->setLabel($field, $config['caption'][$culture].'('.$appCaption.')');
        $this->setDefault($field, Doctrine::getTable('SnsTerm')->findOneByApplicationAndName($app, $name));
      }

      $this->setValidator($name, new sfValidatorPass());
      $this->widgetSchema->setNameFormat('term[%s]');
    }


    $this->validatorSchema->setPostValidator(
      new sfValidatorCallback((array('callback' => array($this, 'validateTerms'))))
    );
  }

  public function save()
  {
    $culture = sfContext::getInstance()->getUser()->getCulture();

    foreach (self::getAvailableTerms() as $name => $config)
    {
      $values = $this->getValue($name);

      foreach ($values as $application => $value)
      {
        Doctrine::getTable('SnsTerm')->set($name, $value, $culture, $application);
      }
    }
  }

  protected static function getAvailableTerms()
  {
    return (array)include(sfContext::getInstance()->getConfigCache()->checkConfig('config/sns_term.yml'));
  }

  public static function validateTerms($validator, $values)
  {
    foreach (self::getAvailableTerms() as $name => $config)
    {
      if (!isset($values[$name]))
      {
        $error = new sfValidatorError($validator, 'required');
        throw new sfValidatorErrorSchema($validator, array($name => $error));
      }

      foreach ($values[$name] as $application => $value)
      {
        if (!isset(self::$availableApplications[$application]))
        {
          $error = new sfValidatorError($validator, 'The specified application is not supported.');
          throw new sfValidatorErrorSchema($validator, array($name => $error));
        }

        $validator = new opValidatorString(array('trim' => true));
        try
        {
          $values[$name][$application] = $validator->clean($value);
        }
        catch (sfValidatorError $e)
        {
          throw new sfValidatorErrorSchema($validator, array($name => $e));
        }
      }
    }

    return $values;
  }
}
