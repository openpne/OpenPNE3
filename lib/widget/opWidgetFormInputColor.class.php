<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opWidgetFormInputColor represents a color widget.
 *
 * @package    OpenPNE
 * @subpackage widget
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opWidgetFormInputColor extends sfWidgetFormInputText
{
  protected function configure($options = array(), $attributes = array())
  {
    parent::configure($options, $attributes);

    $this->addOption('is_display_pre_color', false);
    $this->addOption('color_label', 'After Change');
    $this->addOption('pre_color_label', 'Before Change');
  }

  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    $colorAttributes = array('style' => 'background-color:'.$value, 'id' => 'preview_'.$name, 'class' => 'color');

    $result = $this->renderContentTag('dd', parent::render($name, $value, $attributes, $errors));

    $i18n = sfContext::getInstance()->getI18N();

    if ($this->getOption('is_display_pre_color'))
    {
      $result .= $this->renderContentTag('dd', $this->renderContentTag('div', $i18n->__($this->getOption('pre_color_label'))), array_merge($colorAttributes, array('id' => 'preview_pre_'.$name)));
    }

    $result .= $this->renderContentTag('dd', $this->renderContentTag('div', $i18n->__($this->getOption('color_label'))), $colorAttributes);

    return $result;
  }
}
