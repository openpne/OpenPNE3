<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opChangeCommunityAdminRequestForm
 *
 * @package    OpenPNE
 * @subpackage form
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opCommunityJoiningForm extends sfForm
{
  public function setup()
  {
    $this->setWidget('message', new sfWidgetFormTextarea());
    $this->setValidator('message', new opValidatorString(array('rtrim' => true, 'required' => false)));
    $this->widgetSchema->setLabel('message', 'Message(Arbitrary)');

    $this->widgetSchema->setNameFormat('community_join[%s]');
  }
}
