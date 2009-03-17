<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opWidgetFormPropelChoiceTree
 *
 * @package    OpenPNE
 * @subpackage widget
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opWidgetFormPropelChoiceTree extends sfWidgetFormPropelChoice
{
  /**
   * Returns the choices associated to the model.
   *
   * @return array An array of choices
   */
  public function getChoices()
  {
    $choices = array();
    if (false !== $this->getOption('add_empty'))
    {
      $choices[''] = true === $this->getOption('add_empty') ? '' : $this->getOption('add_empty');
    }

    $class = constant($this->getOption('model').'::PEER');

    $criteria = is_null($this->getOption('criteria')) ? new Criteria() : clone $this->getOption('criteria');
    if ($order = $this->getOption('order_by'))
    {
      $method = sprintf('add%sOrderByColumn', 0 === strpos(strtoupper($order[1]), 'ASC') ? 'Ascending' : 'Descending');
      $criteria->$method(call_user_func(array($class, 'translateFieldName'), $order[0], BasePeer::TYPE_PHPNAME, BasePeer::TYPE_COLNAME));
    }
    $objects = call_user_func(array($class, $this->getOption('peer_method')), $criteria, $this->getOption('connection'));

    $methodKey = $this->getOption('key_method');
    if (!method_exists($this->getOption('model'), $methodKey))
    {
      throw new RuntimeException(sprintf('Class "%s" must implement a "%s" method to be rendered in a "%s" widget', $this->getOption('model'), $methodKey, __CLASS__));
    }

    $methodValue = $this->getOption('method');
    if (!method_exists($this->getOption('model'), $methodValue))
    {
      throw new RuntimeException(sprintf('Class "%s" must implement a "%s" method to be rendered in a "%s" widget', $this->getOption('model'), $methodValue, __CLASS__));
    }

    foreach ($objects as $object)
    {
      $choices[$object->$methodValue()] = array();
      foreach ($object->getChildren() as $child)
      {
        $choices[$object->$methodValue()][$child->$methodKey()] = $child->$methodValue();
      }
    }

    return $choices;
  }
}
