<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * Community Search Form
 *
 * @package    OpenPNE
 * @subpackage filter
 * @author     Shogo Kawahara <kawahara@tejimaya.net>
 */

class CommunitySearchForm extends CommunityFormFilter
{
  public function configure()
  {
    $this->setWidget('id', new sfWidgetFormFilterInput(array('with_empty' => false)));
    $this->setValidator('id', new sfValidatorPass());
    parent::configure();
  }
}
