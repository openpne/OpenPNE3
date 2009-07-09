<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opPDODatabaseSessionStorage
 *
 * @package    OpenPNE
 * @subpackage user
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opPDODatabaseSessionStorage extends sfPDOSessionStorage
{
  public function sessionOpen($path = null, $name = null)
  {
    if (is_string($this->options['database']))
    {
      $this->options['database'] = sfContext::getInstance()->getDatabaseManager()->getDatabase($this->options['database']);
    }

    return parent::sessionOpen($path, $name);
  }
}
