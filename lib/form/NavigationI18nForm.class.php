<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * NavigationI18n form.
 *
 * @package    OpenPNE
 * @subpackage form
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class NavigationI18nForm extends BaseNavigationI18nForm
{
  public function configure()
  {
    $this->setWidgets(array(
      'caption' => new sfWidgetFormInput(),
    ));
  }
}
