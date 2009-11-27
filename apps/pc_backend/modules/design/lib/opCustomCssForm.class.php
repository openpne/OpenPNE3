<?php

/**
 * this file is part of the openpne package.
 * (c) openpne project (http://www.openpne.jp/)
 *
 * for the full copyright and license information, please view the license
 * file and the notice file that were distributed with this source code.
 */

class opCustomCssForm extends sfForm
{
  public function configure()
  {
    $this->setWidget('css', new sfWidgetFormTextarea(array(), array('rows' => '20', 'cols' => '70')));
    $this->setValidator('css', new opValidatorString());

    $this->setDefault('css', Doctrine::getTable('SnsConfig')->get('customizing_css'));

    $this->widgetSchema->setNameFormat('css[%s]');
  }

  public function save()
  {
    Doctrine::getTable('SnsConfig')->set('customizing_css', $this->getValue('css'));
    opToolkit::clearCache();
  }
}
