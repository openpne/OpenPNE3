<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opWidgetFormProfile is widget to edit member's profiles.
 *
 * @package    OpenPNE
 * @subpackage widget
 * @author     Shogo Kawahara <kawahara@tejimaya.net>
 */
class opWidgetFormProfile extends sfWidgetForm
{
  /**
   * Constructor.
   * 
   * @param array $options     An array of options
   * @param array $attributes  An array of default HTML attributes
   *
   * @see sfWidgetForm
   */
  public function __construct($options = array(), $attributes = array())
  {
    $this->addRequiredOption('widget');
    $this->addOption('is_edit_public_flag', false);
    $this->addOption('public_flag_default', 1);
    $this->addOption('template', '%input%<br>%public_flag%');

    parent::__construct($options, $attributes);

    $this->setLabel($this->getOption('widget')->getLabel());
  }

  /**
   * render
   *
   * @param string $name 
   * @param array  $value
   * @param array  $attributes
   * @param array  $errors
   *
   * @return string
   *
   * @see sfWidgetForm
   */
  public function render($name, $value = array(), $attributes = array(), $errors = array())
  {
    if (!is_array($value))
    {
      $value = array('value' => $value, 'public_flag' => ProfileTable::PUBLIC_FLAG_SNS);
    }
    else
    {
      $value = array_merge(array('value' => null, 'public_flag' => ProfileTable::PUBLIC_FLAG_SNS), $value);
    }

    $input = $this->getOption('widget')->render($name.'[value]', $value['value'], $attributes, $errors);
    if (!$this->getOption('is_edit_public_flag'))
    {
      return $input;
    }
    $publicFlagWidget = new sfWidgetFormSelect(array('choices' => Doctrine::getTable('Profile')->getPublicFlags()));

    return strtr($this->getOption('template'), array('%input%' => $input, '%public_flag%' =>$publicFlagWidget->render($name.'[public_flag]', $value['public_flag'], $attributes, $errors)));
  }
}
