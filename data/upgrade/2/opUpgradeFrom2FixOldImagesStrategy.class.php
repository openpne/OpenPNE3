<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * The upgrating strategy by fixing converted file and file_bin for images.
 *
 * @package    OpenPNE
 * @subpackage task
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opUpgradeFrom2FixOldImagesStrategy extends opUpgradeAbstractStrategy
{
  protected $tmpConn = null;

  protected function resetTmpConnection()
  {
    if ($this->tmpConn)
    {
      $this->tmpConn->close();
    }

    $dsn = $this->conn->getOption('dsn');
    $info = $this->conn->getManager()->parsePdoDsn($dsn);
    $this->conn->export->tmpConnectionDatabase = $info['dbname'];

    $this->tmpConn = $this->conn->getTmpConnection($info);
  }

  public function run()
  {
    $this->getDatabaseManager();
    $this->conn = Doctrine_Manager::connection();

    $convertingCount = 100;
    $imageSize = IMAGE_MAX_FILESIZE * 2 * 1024;
    if (opToolkit::calculateUsableMemorySize())
    {
      $convertingCount = (int)(opToolkit::calculateUsableMemorySize() / $imageSize);
    }

    $this->conn->beginTransaction();
    try
    {
      $this->fixMimeType();
      $this->fixBinary($convertingCount);

      $this->conn->commit();
    }
    catch (Exception $e)
    {
      $this->conn->rollback();

      throw $e;
    }
  }

  protected function fixMimeType()
  {
    $result = $this->conn->fetchAll('SELECT * FROM file');
    foreach ($result as $k => $v)
    {
      $ext = $v['type'];
      if (!$ext)
      {
        $pieces = explode('.', $v['name'], 2);
        if (is_array($pieces))
        {
          $ext = array_pop($pieces);
        }
      }

      if ($ext)
      {
        if (!in_array($ext, array('jpeg', 'png', 'gif')))
        {
          $ext = 'jpeg';
        }

        $type = 'image/'.$ext;
        $this->conn->execute('UPDATE file SET type = ? WHERE id = ?', array($type, $v['id']));
      }
    }
  }

  protected function fixBinary($size)
  {
    $count = $this->conn->fetchOne('SELECT COUNT(id) FROM file');

    for ($i = 0; $i < ($count / $size); $i++)
    {
      $this->doFixBinary(($i * $size), $size);
    }
  }

  protected function doFixBinary($offset, $limit)
  {
    $result = $this->conn->fetchColumn('SELECT id FROM file LIMIT '.$limit.' OFFSET '.$offset);
    foreach ($result as $id)
    {
      $fileBin = $this->conn->fetchOne('SELECT bin FROM file_bin WHERE file_id = ?', array($id));
      $fileBin = base64_decode($fileBin);
      $this->conn->execute('UPDATE file_bin SET bin = ? WHERE file_id = ?', array($fileBin, $id));
      $this->conn->execute('UPDATE file SET filesize = ? WHERE id = ?', array(strlen($fileBin), $id));
    }
  }
}
