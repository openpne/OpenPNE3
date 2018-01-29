<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * AddApplicationForm
 *
 * @package    opOpenSocialPlugin
 * @subpackage form
 * @author     Shogo Kawahara <kawahara@tejimaya.net>
 */
class AddApplicationForm extends sfForm
{
  public function configure()
  {
    $this->setWidgets(array(
      'application_url' => new sfWidgetFormInput(),
    ));
    $this->setValidators(array(
      'application_url'      => new sfValidatorString(array(),array()),
    ));
    $this->widgetSchema->setLabel('application_url', 'Gadget XML URL');
    $this->widgetSchema->setNameFormat('contact[%s]');
  }
}
