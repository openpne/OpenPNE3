<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * Gadget Sort Form.
 *
 * @package    OpenPNE
 * @subpackage form
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class GadgetSortForm extends sfForm
{
  public function configure()
  {
    $gadgets = $this->getOption('current_gadgets', array());

    foreach ($gadgets as $key => $value)
    {
      $this->setValidator($key, new sfValidatorCallback(array('callback' => array($this, 'validate'))));
    }

    $this->getWidgetSchema()->setNameFormat('gadget[%s]');
  }

  public function save()
  {
    foreach ($this->values as $type => $gadgets)
    {
      $ids = GadgetPeer::getGadgetsIds($type);
      if (!$gadgets)
      {
        $gadgets = array();
      }

      foreach ($ids as $id)
      {
        $gadget = GadgetPeer::retrieveByPk($id);
        $key = array_search($id, $gadgets);

        if ($key === false)
        {
          $gadget->delete();
          continue;
        }

        if ($gadget)
        {
          $sortOrder = ((int)$key + 1) * 10;
          $gadget->setSortOrder($sortOrder);
          $gadget->save();
        }
      }
    }
  }

  public function validate($validator, $value)
  {
    $result = array();

    foreach ($value as $id)
    {
      $gadget = GadgetPeer::retrieveByPk($id);
      if ($gadget) 
      {
        if (array_key_exists($gadget->getName(), sfConfig::get('op_gadget_list'))
          || array_key_exists($gadget->getName(), sfConfig::get('op_login_gadget_list'))
          || array_key_exists($gadget->getName(), sfConfig::get('op_mobile_gadget_list'))
          || array_key_exists($gadget->getName(), sfConfig::get('op_side_banner_gadget_list')))
        {
          $result[] = $id;
        }
      }
    }

    return $result;
  }
}
