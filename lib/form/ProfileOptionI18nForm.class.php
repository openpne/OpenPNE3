<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * ProfileOptionI18n form.
 *
 * @package    form
 * @subpackage profile_option_i18n
 * @version    SVN: $Id: sfPropelFormTemplate.php 6174 2007-11-27 06:22:40Z fabien $
 */
class ProfileOptionI18nForm extends BaseProfileOptionI18nForm
{
  public function configure()
  {
    $this->setWidgets(array(
      'value' => new sfWidgetFormInput(),
    ));
  }
}
