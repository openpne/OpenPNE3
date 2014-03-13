<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * TimelineDataForm
 *
 * @package    OpenPNE
 * @subpackage opTimelinePlugin
 */

class TimelineDataForm extends BaseActivityDataForm
{
  public function configure()
  {
    $this->useFields(array('body', 'in_reply_to_activity_id', 'foreign_table', 'foreign_id'));
    if (sfConfig::get('sf_app') != 'mobile_frontend')
    {
      $this->setWidget('body', new sfWidgetFormTextarea());
    }
    $this->setValidator('body', new opValidatorString(array('max_length' => 140, 'required' => true, 'trim' => true)));

    $this->setWidget('in_reply_to_activity_id', new sfWidgetFormInputHidden());
    $this->setWidget('foreign_table', new sfWidgetFormInputHidden());
    $this->setWidget('foreign_id', new sfWidgetFormInputHidden());

    $this->setWidget('next_uri', new opWidgetFormInputHiddenNextUri());
    $this->setValidator('next_uri', new opValidatorNextUri());
  }
}
