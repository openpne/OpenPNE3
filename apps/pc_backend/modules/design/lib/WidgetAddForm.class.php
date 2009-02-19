<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * Gadget Add Form.
 *
 * @package    OpenPNE
 * @subpackage form
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class GadgetAddForm extends sfForm
{
  public function configure()
  {
    $gadgets = $this->getOption('current_gadgets', array());

    foreach ($gadgets as $key => $value)
    {
      $this->setValidator($key, new sfValidatorCallback(array('callback' => array($this, 'validate'))));
    }

    $this->getWidgetSchema()->setNameFormat('new[%s]');
  }

  public function save()
  {
    foreach ($this->values as $type => $gadgets)
    {
      if (!$gadgets)
      {
        continue;
      }

      foreach ($gadgets as $value)
      {
        $gadget = new Gadget();
        $gadget->setType($type);
        $gadget->setName($value);
        $gadget->save();
      }
    }
  }

  public function validate($validator, $value)
  {
    $result = array();

    foreach ($value as $key => $item)
    {
      if (array_key_exists($item, sfConfig::get('op_gadget_list'))
        || array_key_exists($item, sfConfig::get('op_login_gadget_list'))
        || array_key_exists($item, sfConfig::get('op_mobile_gadget_list'))
        || array_key_exists($item, sfConfig::get('op_side_banner_gadget_list')))
      {
        $result[] = $item;
      }
    }

    return $result;
  }
}
