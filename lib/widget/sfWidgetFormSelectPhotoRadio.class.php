<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
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
