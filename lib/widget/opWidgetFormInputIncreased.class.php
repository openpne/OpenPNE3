<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opWidgetFormInputIncreased represents a date widget.
 *
 * @package    OpenPNE
 * @subpackage widget
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opWidgetFormInputIncreased extends sfWidgetForm
{
  public function __construct($options = array(), $attributes = array())
  {
    $this->addOption('type', 'text');

    parent::__construct($options, $attributes);
  }

  /**
   * Renders this widget
   *
   * @param  string $name        The element name
   * @param  array  $value       The value displayed in this widget
   * @param  array  $attributes  An array of HTML attributes to be merged with the default HTML attributes
   * @param  array  $errors      An array of errors for the field
   *
   * @return string An HTML tag string
   *
   * @see sfWidgetForm
   */
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    if (empty($value))
    {
      $value = array();
    }
    $value[] = '';

    if (!is_array($value))
    {
      throw new InvalidArgumentException('value is must be an array.');
    }

    $result = '';

    foreach ($value as $key => $item)
    {
      $params = array(
        'type'  => $this->getOption('type'),
        'name'  => $name.'['.$key.']',
        'value' => $item,
        'class' => 'input_'.$this->getOption('type'),
      );
      $input_tag = $this->renderTag('input', array_merge($params, $attributes));
      $result .= $this->renderContentTag('li', $input_tag);
    }

    return $this->renderContentTag('ul', $result);
  }
}
