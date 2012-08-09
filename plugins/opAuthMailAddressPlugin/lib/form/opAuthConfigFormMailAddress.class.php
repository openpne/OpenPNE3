<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opAuthConfigFormMailAddress represents a form to configuration.
 *
 * @package    OpenPNE
 * @subpackage form
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opAuthConfigFormMailAddress extends opAuthConfigForm
{
  public function setup()
  {
    parent::setup();

    $this->widgetSchema->setHelp(
      'is_check_multiple_address',
      'どちらか一方を受け付ける設定とした場合、携帯メールアドレスを利用しているスマートフォンユーザーがログインできなくなります。'
    );
  }
}
