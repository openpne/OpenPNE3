<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * openpneFixSessionDbTask
 *
 * @package    OpenPNE
 * @subpackage task
 * @author     Kousuke Ebihara <ebihara@php.net>
 */
class openpneFixSessionDbTask extends sfDoctrineBaseTask
{
  protected function configure()
  {
    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', true),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
    ));

    $this->namespace        = 'openpne';
    $this->name             = 'fix-session-db';
    $this->briefDescription = 'Fix serious bug in managing session with your database';
    $this->detailedDescription = <<<EOF
The [openpne:fix-session-db|INFO] task fixes serious bug in managing session with your database.
Call it with:

  [./symfony openpne:fix-session-db|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    $this->logSection('fix-session-db', 'Begin to fix session table structure');

    $this->openDatabaseConnection();

    $this->logSection('fix-session-db', 'Now changing definition of your session table structure');

    $conn = opDoctrineQuery::getMasterConnectionDirect();
    $conn->export->alterTable('session', array(
      'change' => array(
        'id' => array(
          'definition' => array(
            'type'    => 'string',
            'length'  => 128,
            'primary' => '1',
          ),
        ),
      ),
    ));

    $this->logSection('fix-session-db', 'Clear current session data');
    $conn->execute('TRUNCATE session');

    $this->logSection('fix-session-db', 'Finish to fix session table structure');
  }

  protected function openDatabaseConnection()
  {
    new sfDatabaseManager($this->configuration);
  }
}
