<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opDesignHtmlForm
 *
 * @package    OpenPNE
 * @subpackage form
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opDesignHtmlForm extends sfForm
{
  const DEFAULT_TYPE = 'footer_after';

  public function configure()
  {
    $this->setWidget('html', new opWidgetFormRichTextarea(array(
      'config' => array('theme_advanced_buttons1' => 'fontsizeselect, bold, italic, undefined, forecolor, hr, link, image'),
    ), array('rows' => '20', 'cols' => '70')));

    $this->setValidator('html', new opValidatorString(array('required' => false)));

    $this->widgetSchema->setNameFormat('design_html[%s]');
  }

  public function save()
  {
    if (!in_array($this->getOption('type'), self::allowedTypeList()))
    {
      throw new RuntimeException(sprintf('The specified type "%s" is not allowed.', $this->getOption('type')));
    }

    Doctrine::getTable('SnsConfig')->set($this->getOption('type'), $this->getValue('html'));
  }

  static public function allowedTypeList()
  {
    return array(
      'footer_before', 'footer_after', 'pc_html_head', 'pc_html_top',
      'pc_html_top2', 'pc_html_bottom', 'pc_html_bottom2', 'mobile_html_head',
      'mobile_header', 'mobile_footer',
    );
  }
}
