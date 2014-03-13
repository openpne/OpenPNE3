<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * Container Config Form.
 *
 * @package    OpenPNE
 * @subpackage form
 * @author     Shogo Kawahara <kawahara@tejimaya.net>
 */
class ContainerConfigForm extends sfForm
{
  public function configure()
  {
    $snsConfigTable = Doctrine::getTable('SnsConfig');
    $this->setWidgets(array(
      'shindig_token_cipher_key' => new sfWidgetFormInput(),
      'shindig_token_hmac_key'   => new sfWidgetFormInput(),
      'shindig_token_max_age'    => new sfWidgetFormInput(),
      'shindig_cache_time'       => new sfWidgetFormInput(),
      'is_use_outer_shindig'     => new sfWidgetFormInputCheckbox(),
      'shindig_url'              => new sfWidgetFormInput(),
    ));

    $this->setValidators(array(
      'shindig_token_cipher_key' => new sfValidatorString(),
      'shindig_token_hmac_key'   => new sfValidatorString(),
      'shindig_token_max_age'    => new sfValidatorInteger(array('min' => 0)),
      'shindig_cache_time'       => new sfValidatorInteger(array('min' => 0)),
      'is_use_outer_shindig'     => new sfValidatorBoolean(),
      'shindig_url'              => new sfValidatorString(array('required' => false)),
    ));

    $this->validatorSchema->setPostValidator(new sfValidatorCallback(array(
      'callback' => array('ContainerConfigForm', 'validate'),
    )));

    $this->setDefaults(array(
      'shindig_token_cipher_key' => $snsConfigTable->get('shindig_token_cipher_key'),
      'shindig_token_hmac_key'   => $snsConfigTable->get('shindig_token_hmac_key'),
      'shindig_token_max_age'    => $snsConfigTable->get('shindig_max_token_age', 60*60),
      'shindig_cache_time'       => $snsConfigTable->get('shindig_cache_time', 24*60*60),
      'is_use_outer_shindig'     => (bool)$snsConfigTable->get('is_use_outer_shindig', false),
      'shindig_url'              => $snsConfigTable->get('shindig_url'),
    ));

    $this->widgetSchema->setLabels(array(
      'shindig_token_cipher_key'    => 'Token cipher key',
      'shindig_token_hmac_key'      => 'Token hmac key',
      'shindig_token_max_age'   => 'Token max age (s)',
      'shindig_cache_time'      => 'Cache time (s)',
      'is_use_outer_shindig'    => 'Use outer OpenSocial container (Recomended)',
      'shindig_url'             => 'The OpenSocial container URL',
    ));

    $this->widgetSchema->setNameFormat('container_config[%s]');
  }

  public static function validate($validator, $values, $argments = array())
  {
    $result = array();

    if ($values['is_use_outer_shindig'])
    {
      if (empty($values['shindig_url']))
      {
        throw new sfValidatorError($validator, 'invalid');
      }
    }

    return $values;
  }

  public function save()
  {
    foreach ($this->getValues() as $key => $value)
    {
      $snsConfig = Doctrine::getTable('SnsConfig')->findOneByName($key);
      if (!$snsConfig)
      {
        $snsConfig = new SnsConfig();
        $snsConfig->setName($key);
      }
      $snsConfig->setValue($value);
      $snsConfig->save();
    }
    return true;
  }
}
