<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * GadgetConfig form.
 *
 * @package    OpenPNE
 * @subpackage form
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class GadgetConfigForm extends BaseForm
{
  protected $gadget;

  public function __construct(Gadget $gadget, $options = array(), $CSRFSecret = null)
  {
    $this->gadget = $gadget;

    parent::__construct(array(), $options, $CSRFSecret);

    $config = Doctrine::getTable('Gadget')->getGadgetConfigListByType($options['type']);
    if (empty($config[$gadget->getName()]['config']))
    {
      throw new RuntimeException('The gadget has not registered or it doesn\'t have any configuration items.');
    }

    $gadgetConfig = $config[$gadget->getName()]['config'];
    foreach ($gadgetConfig as $key => $value)
    {
      $this->setWidget($key, opFormItemGenerator::generateWidget($value));
      $this->setValidator($key, opFormItemGenerator::generateValidator($value));

      $config = Doctrine::getTable('GadgetConfig')->retrieveByGadgetIdAndName($gadget->getId(), $key);
      if ($config)
      {
        $this->setDefault($key, $config->getValue());
      }
    }

    $this->widgetSchema->setNameFormat('gadget_config[%s]');
  }

  public function save()
  {
    foreach ($this->values as $key => $value)
    {
      $gadgetConfig = Doctrine::getTable('GadgetConfig')->retrieveByGadgetIdAndName($this->gadget->getId(), $key);
      if (!$gadgetConfig)
      {
        $gadgetConfig = new GadgetConfig();
        $gadgetConfig->setGadget($this->gadget);
        $gadgetConfig->setName($key);
      }
      $gadgetConfig->setValue($value);
      $gadgetConfig->save();
    }
  }
}
