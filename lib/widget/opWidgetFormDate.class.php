<?php

/**
 * Copyright (C) 2005-2009 OpenPNE Project
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *  http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/**
 * opWidgetFormDate represents a date widget.
 *
 * @package    OpenPNE
 * @subpackage widget
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opWidgetFormDate extends sfWidgetFormI18nDate
{
 /**
  * @see sfWidgetFormI18nDate
  */
  protected function getDateFormat($culture)
  {
    $result = parent::getDateFormat($culture);
    return str_replace('%year%', '%input_year%', $result);
  }

  /**
   * @see sfWidgetFormDate
   */
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    if (is_array($value))
    {
      $value = sprintf('%04d-%02d-%02d', $value['year'], $value['month'], $value['day']);
    }

    $dateTime = new DateTime($value);
    if (!$dateTime)
    {
      throw new sfException('Invaid date format.');
    }

    // days
    $widget = new sfWidgetFormSelect(array('choices' => $this->getOption('days')), array_merge($this->attributes, $attributes));
    $date['%day%'] = $widget->render($name.'[day]', $dateTime->format('j'));

    // months
    $widget = new sfWidgetFormSelect(array('choices' => $this->getOption('months')), array_merge($this->attributes, $attributes));
    $date['%month%'] = $widget->render($name.'[month]', $dateTime->format('n'));

    // years
    $attributes['size'] = '5';
    $widget = new sfWidgetFormInput(array(), array_merge($this->attributes, $attributes));
    $date['%input_year%'] = $widget->render($name.'[year]', $dateTime->format('Y'));

    return strtr($this->getOption('format'), $date);

  }
}
