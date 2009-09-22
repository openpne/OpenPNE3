<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * fileDeleteForm
 *
 * @package    OpenPNE
 * @subpackage form
 * @author     Shinichi Urabe <urabe@tejimaya.net>
 */
class FileDeleteForm extends BaseFileForm
{
  public function configure()
  {
    $this->setWidgets(array('fileId' => new sfWidgetFormInputHidden()));
    $this->setValidators(array('fileId' => new sfValidatorInteger()));

    $this->widgetSchema->setNameFormat('file[%s]');
  }
}
