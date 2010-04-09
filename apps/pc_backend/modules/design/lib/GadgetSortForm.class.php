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
      $this->setValidator($key, new sfValidatorCallback(array(
        'callback' => array($this, 'validate'),
        'arguments' => array('type' => $key)
      )));
    }

    $this->getWidgetSchema()->setNameFormat('gadget[%s]');
  }

  public function save()
  {
    foreach ($this->values as $type => $gadgets)
    {
      $ids = Doctrine::getTable('Gadget')->getGadgetsIds($type);
      if (!$gadgets)
      {
        $gadgets = array();
      }

      foreach ($ids as $id)
      {
        $gadget = Doctrine::getTable('Gadget')->find($id);
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

  public function validate($validator, $value, $args)
  {
    $result = array();
    $gadgetList = Doctrine::getTable('Gadget')->getGadgetConfigListByType($args['type']);
    foreach ($value as $id)
    {
      $gadget = Doctrine::getTable('Gadget')->find($id);
      if ($gadget)
      {
        $item = $gadget->getName();
        if (array_key_exists($item, $gadgetList))
        {
          $result[] = $id;
        }
      }
    }

    return $result;
  }
}
