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
 * sfWidgetFormSelectPhotoRadio represents radio HTML tags for selection photos.
 *
 * @package    OpenPNE
 * @subpackage widget
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class sfWidgetFormSelectPhotoRadio extends sfWidgetFormSelectRadio
{
  protected function configure($options = array(), $attributes = array())
  {
    $this->addRequiredOption('image_prefix');
    parent::configure($options, $attributes);
  }

  public function formatter($widget, $inputs)
  {
    sfContext::getInstance()->getConfiguration()->loadHelpers('Asset');

    $rows = array();
    foreach ($inputs as $key => $input)
    {
      $image_option = array(
        'src' => image_path($this->getOption('image_prefix').$key),
        'alt' => $this->getOption('image_prefix').$key,
      );
      $image = $this->renderTag('img', $image_option);
      $list = $this->renderContentTag('dt', $image)
              . $this->renderContentTag('dd', $input['input'].$this->getOption('label_separator').$input['label']);
      $rows[] = $this->renderContentTag('dl', $list);
    }

    return $this->renderContentTag('div', implode($this->getOption('separator'), $rows), array('class' => $this->getOption('class')));
  }
}
