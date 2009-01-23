<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class opUpdate_3_0beta4_dev_200901221618_to_3_0beta4_dev_200901232301 extends opUpdate
{
  public function update()
  {
    // opAshiatoPlugin
    $task = new openpneUpdateTask($this->dispatcher, $this->formatter);
    $task->run(array(
      'name' => 'opAshiatoPlugin',
      'before-version' => '1.0beta4_dev_200901221242',
      'after-version' => '1.0beta4_dev_200901232301',
    ), array('--no-build-model'));
  }
}
