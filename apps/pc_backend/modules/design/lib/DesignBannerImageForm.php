<?php

/**
 * DesignBannerImageForm
 *
 * @package    OpenPNE
 * @subpackage form
 * @author     Masato Nagasawa <nagasawa@tejimaya.com>
 */
class DesignBannerImageForm extends sfForm
{
  protected $key = 'footer_';

  public function configure()
  {
    $type = $this->getOption('type');
    $this->key .= $type;
    $snsConfig = Doctrine::getTable('SnsConfig')->findByName($this->key);

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

    $snsConfig = Doctrine::getTable('SnsConfig')->findByName($this->key);
    if (!$snsConfig)
    {
      $snsConfig = new SnsConfig();
      $snsConfig->setName($this->Key);
    }
    $snsConfig->setValue($values[$type]);
    $snsConfig->save();
  }
}
