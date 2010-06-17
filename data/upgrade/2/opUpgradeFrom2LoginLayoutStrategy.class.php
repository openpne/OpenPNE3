<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * The upgrating strategy for fixing login layout.
 *
 * @package    OpenPNE
 * @subpackage task
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opUpgradeFrom2LoginLayoutStrategy extends opUpgradeAbstractStrategy
{
  protected $portalPositions = array(
    'loginTop'      => array(10, 14),
    'loginSideMenu' => array(20, 29),
    'loginContents' => array(30, 39),
    'loginBottom'   => array(40, 44),
  );

  protected $portalContents = array(
    'rss'  => array(
      1 => 'RSS1',
      2 => 'RSS2',
      3 => 'RSS3',
      4 => 'RSS4',
      5 => 'RSS5',
    ),
    'free' => array(
      1 => 'FREE1',
      2 => 'FREE2',
      3 => 'FREE3',
      4 => 'FREE4',
      5 => 'FREE5'
    ),
    'link' => array('LINK'),
  );

  public function run()
  {
    $this->getDatabaseManager();
    $this->conn = Doctrine_Manager::connection();

    $this->conn->beginTransaction();
    try
    {
      $this->doRun();

      $this->conn->commit();
    }
    catch (Exception $e)
    {
      $this->conn->rollback();

      throw $e;
    }
  }

  public function doRun()
  {
    if (!(bool)$this->conn->fetchOne('SELECT value FROM portal_config WHERE name = ?', array('USE_PORTAL')))
    {
      $this->convertOldTypeLoginPage();
    }

    $this->convertPortalLoginPage();
  }

  public function convertOldTypeLoginPage()
  {
    $this->conn->execute('INSERT INTO sns_config (id, name, value) VALUES(NULL, ?, ?)', array('external_pc_login_url', '@opSkinClassicPlugin_login'));
  }

  public function convertPortalLoginPage()
  {
    $portlets = $this->conn->fetchAssoc('SELECT content_name, position FROM portal_layout');

    foreach ($portlets as $portlet)
    {
      $type = $this->findContentType($portlet['content_name']);
      if (is_null($type))
      {
        continue;
      }

      $target = $this->getGadgetTarget($portlet['position']);
      if (is_null($target))
      {
        continue;
      }

      if ('free' === $type)
      {
        $freeAreaId = array_search($portlet['content_name'], $this->portalContents['free']);
        $this->conn->execute('INSERT INTO gadget (id, type, name, sort_order, created_at, updated_at) VALUES(NULL, ?, ?, ?, NOW(), NOW())', array($target, 'freeArea', $portlet['position']));

        $gadgetId = $this->conn->lastInsertId();
        $this->conn->execute('INSERT INTO gadget_config (id, name, gadget_id, value, created_at, updated_at) (SELECT NULL, ?, ?, html, NOW(), NOW() FROM portal_free_area WHERE portal_free_area_id = ?)', array('value', $gadgetId, $freeAreaId));
      }
      elseif ('rss' === $type)
      {
        $rssId = array_search($portlet['content_name'], $this->portalContents['rss']);
        $this->conn->execute('INSERT INTO gadget (id, type, name, sort_order, created_at, updated_at) VALUES(NULL, ?, ?, ?, NOW(), NOW())', array($target, 'rssBox', $portlet['position']));

        $gadgetId = $this->conn->lastInsertId();
        $this->conn->execute('INSERT INTO gadget_config (id, name, gadget_id, value, created_at, updated_at) (SELECT NULL, ?, ?, name, NOW(), NOW() FROM portal_rss WHERE portal_rss_id = ?)', array('title', $gadgetId, $rssId));
        $this->conn->execute('INSERT INTO gadget_config (id, name, gadget_id, value, created_at, updated_at) (SELECT NULL, ?, ?, url, NOW(), NOW() FROM portal_rss WHERE portal_rss_id = ?)', array('url', $gadgetId, $rssId));
      }
      elseif ('link' === $type)
      {
        $this->conn->execute('INSERT INTO gadget (id, type, name, sort_order, created_at, updated_at) VALUES(NULL, ?, ?, ?, NOW(), NOW())', array($target, 'linkListBox', $portlet['position']));
        $gadgetId = $this->conn->lastInsertId();
        for ($i = 1; $i <= 10; $i++)
        {
          $this->conn->execute('INSERT INTO gadget_config (id, name, gadget_id, value, created_at, updated_at) (SELECT NULL, ?, ?, url, NOW(), NOW() FROM portal_link WHERE portal_link_id = ?)', array('url'.$i, $gadgetId, $i));
          $this->conn->execute('INSERT INTO gadget_config (id, name, gadget_id, value, created_at, updated_at) (SELECT NULL, ?, ?, title, NOW(), NOW() FROM portal_link WHERE portal_link_id = ?)', array('text'.$i, $gadgetId, $i));
        }
        $this->conn->execute('INSERT INTO gadget_config (id, name, gadget_id, value, created_at, updated_at) VALUES(NULL, ?, ?, ?, NOW(), NOW())', array('title', $gadgetId, 'リンク集'));
      }
    }
  }

  protected function findContentType($contentName)
  {
    $type = null;
    foreach ($this->portalContents as $typeName => $contents)
    {
      if (in_array($contentName, $contents))
      {
        $type = $typeName;
        break;
      }
    }

    return $type;
  }

  protected function getGadgetTarget($position)
  {
    $target = null;
    foreach ($this->portalPositions as $k => $v)
    {
      if ($position >= $v[0] && $position <= $v[1])
      {
        $target = $k;
        break;
      }
    }

    return $target;
  }
}
