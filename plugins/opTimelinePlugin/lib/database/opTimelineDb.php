<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opTimelineDb
 *
 * @package    OpenPNE
 * @subpackage opTimelinePlugin
 */

class opTimelineDb
{

  public static function findVariableOfMySQL($name)
  {
    $pdo = self::createPDOInstance();

    $sth = $pdo->prepare('SHOW VARIABLES LIKE ?');
    $sth->execute(array($name));
    $searchResult = $sth->fetch();

    if (false === $searchResult)
    {
      return false;
    }

    return $searchResult['Value'];
  }

  public static function createPDOInstance()
  {
    $configuration = sfContext::getInstance()->getConfiguration();

    $config = ProjectConfiguration::getApplicationConfiguration(
                    $configuration->getApplication(),
                    $configuration->getEnvironment(),
                    $configuration->isDebug());

    $dbManager = new sfDatabaseManager($config);

    $names = $dbManager->getNames();

    $db = $dbManager->getDatabase($names[0]);

    return new PDO($db->getParameter('dsn'), $db->getParameter('username'), $db->getParameter('password'));
  }

}
