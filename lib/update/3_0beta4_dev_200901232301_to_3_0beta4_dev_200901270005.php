<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class opUpdate_3_0beta4_dev_200901232301_to_3_0beta4_dev_200901270005 extends opUpdate
{
  public function update()
  {
    // opCommunityTopicPlugin
    $task = new openpneUpdateTask($this->dispatcher, $this->formatter);
    $task->run(array(
      'name' => 'opCommunityTopicPlugin',
      'before-version' => '0.5',
      'after-version' => '0.6_dev_20091270005',
    ), array('--no-build-model'));
  }
}
