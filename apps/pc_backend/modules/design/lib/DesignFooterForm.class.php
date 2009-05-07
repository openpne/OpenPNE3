<?php

/**
 * AdminUserEditPasswordForm
 *
 * @package    OpenPNE
 * @subpackage form
 * @author     Shingo Yamada <s.yamada@tejimaya.com>
 */
class DesignFooterForm extends sfForm
{
  protected $key = 'footer_';

  public function configure()
  {
    $type = $this->getOption('type');
    $this->key .= $type;
    $snsConfig = SnsConfigPeer::retrieveByName($this->key);

    $this->setWidgets(array(
      $type => new sfWidgetFormTextarea(),
    ));
    $this->setDefault($type, $snsConfig->getValue());
    $this->widgetSchema->setNameFormat('design_footer[%s]');

    $this->setValidators(array(
      'before' => new sfValidatorPass(),
      'after' => new sfValidatorPass(),
    ));
  }

  public function save()
  {
    $type = $this->getOption('type');
    $values = $this->getValues();

    $snsConfig = SnsConfigPeer::retrieveByName($this->key);
    if (!$snsConfig)
    {
      $snsConfig = new SnsConfig();
      $snsConfig->setName($this->Key);
    }
    $snsConfig->setValue($values[$type]);
    $snsConfig->save();
  }
}
