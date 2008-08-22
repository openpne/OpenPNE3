<?php

/**
 * PCAddress form.
 *
 * @package    OpenPNE
 * @subpackage form
 * @author     Kousuke Ebihara <ebihara@tejimaya.net>
 */
class PCAddressForm extends MemberConfigForm
{
  public function configure()
  {
    parent::configure();

    $settings = $this->getSettings();

    $this->setWidgets(array('pc_address' => $this->generateWidget($settings['pc_address'])));
    $this->setValidators(array('pc_address' => $this->generateValidator($settings['pc_address'])));
    $this->widgetSchema->setLabels(array('pc_address' => $settings['pc_address']['Caption']));

    $this->validatorSchema['pc_address_confirm'] = $this->validatorSchema['pc_address'];
    $this->widgetSchema['pc_address_confirm'] = $this->widgetSchema['pc_address'];

    $this->validatorSchema->setPostValidator(new sfValidatorSchemaCompare('pc_address', '==', 'pc_address_confirm'));
    $this->validatorSchema->setPostValidator(new sfValidatorCallback(array('callback' => array('PCAddressForm', 'isUniquePCAddress'))));

    $this->widgetSchema->setNameFormat('pc_address[%s]');
  }

  public static function isUniquePCAddress($validator, $value, $arguments = array())
  {
    $data = MemberConfigPeer::retrieveByNameAndValue('pc_address', $value['pc_address']);

    if (!$data || !$data->getMember()->getIsActive()) {
      return $value;
    }

    throw new sfValidatorError($validator, 'This E-mail address already exists.');
  }
}
