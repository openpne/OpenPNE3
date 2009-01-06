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
 * opWidgetFormInputIncreased represents a date widget.
 *
 * @package    OpenPNE
 * @subpackage widget
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opWidgetFormInputIncreased extends sfWidgetForm
{
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
      );
      $input_tag = $this->renderTag('input', array_merge($params, $attributes));
      $result .= $this->renderContentTag('li', $input_tag);
    }

    return $this->renderContentTag('ul', $result);
  }
}
