<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class opOpenSocialPluginUpdate_0_8_1_to_0_8_2_dev_200902121145 extends opUpdate
{
  public function update()
  {
    $this->dropTable('application_persistent_data');
  }
}
