<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * ActivityData form.
 *
 * @package    OpenPNE
 * @subpackage form
 * @author     Shogo Kawahara <kawahara@tejimaya.com>
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ActivityDataForm extends BaseActivityDataForm
{
  public function configure()
  {
    $this->useFields(array('body', 'public_flag'));
    if (sfConfig::get('sf_app') != 'mobile_frontend')
    {
      $this->setWidget('body', new sfWidgetFormTextarea());
    }
    $this->setValidator('body', new opValidatorString(array('max_length' => 140, 'required' => true, 'trim' => true)));

    $choices = $this->getObject()->getTable()->getPublicFlags();
    $this->setWidget('public_flag', new sfWidgetFormChoice(array('choices' => $choices)));
    $this->setValidator('public_flag', new sfValidatorChoice(array('choices' => array_keys($choices))));

    $this->setWidget('next_uri', new opWidgetFormInputHiddenNextUri());
    $this->setValidator('next_uri', new opValidatorNextUri());
  }
}
