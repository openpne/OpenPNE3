<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class Revision48_DeleteUnusedGoogleApiKey extends Doctrine_Migration_Base
{
  public function up()
  {
    Doctrine_Query::create()->delete('SnsConfig')
      ->where('name = ?', 'google_AJAX_search_api_key')
      ->execute();
  }
}
