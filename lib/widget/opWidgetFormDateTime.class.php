<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opWidgetFormDateTime
 *
 * @package     OpenPNE3
 * @subpackage  widget
 * @author      Kimura Youichi <kim.upsilon@gmail.com>
 */
class opWidgetFormDateTime extends sfWidgetFormI18nDateTime
{
  /**
   * @see sfWidgetFormI18nDateTime
   */
  public function getDateWidget($attributes = array())
  {
    return new opWidgetFormDate(array_merge(array('culture' => $this->getOption('culture')), $this->getOptionsFor('date')), $this->getAttributesFor('date', $attributes));
  }
}

