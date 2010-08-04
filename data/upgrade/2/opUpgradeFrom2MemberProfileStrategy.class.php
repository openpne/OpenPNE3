<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * The upgrating strategy by importing member's profile.
 *
 * @package    OpenPNE
 * @subpackage task
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opUpgradeFrom2MemberProfileStrategy extends opUpgradeAbstractStrategy
{
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
    $ids = $this->conn->fetchColumn('SELECT c_profile_id FROM c_profile WHERE name NOT IN (?, ?, ?, ?)', array('self_intro', 'PNE_POINT', 'PNE_MY_NEWS', 'PNE_MY_NEWS_DATETIME'));
    $idStr = implode(',', array_fill(0, count($ids), '?'));

    $this->importProfile($ids, $idStr);
    $this->conn->execute('INSERT INTO profile_translation (id, caption, info, lang) (SELECT c_profile_id, caption, info, "ja_JP" FROM c_profile WHERE c_profile_id IN ('.$idStr.')) LIMIT 16', $ids);

    $this->conn->execute('INSERT INTO profile_option (id, profile_id, sort_order, created_at, updated_at) (SELECT c_profile_option_id, c_profile_id, sort_order, NOW(), NOW() FROM c_profile_option WHERE c_profile_id IN ('.implode(',', array_fill(0, count($ids), '?')).'))', $ids);
    $this->conn->execute('INSERT INTO profile_option_translation (id, value, lang) (SELECT c_profile_option_id, value, "ja_JP" FROM c_profile_option WHERE c_profile_id IN ('.implode(',', array_fill(0, count($ids), '?')).'))', $ids);

    $this->conn->execute('INSERT INTO member_profile (id, member_id, profile_id, profile_option_id, value, value_datetime, public_flag, tree_key, lft, rgt, level, created_at, updated_at) (SELECT c_member_profile_id, c_member_id, c_profile_id, NULL, value, NULL, public_flag, c_member_profile_id, 1, 2, 0, NOW(), NOW() FROM c_member_profile WHERE c_profile_id IN ('.$idStr.') AND c_profile_option_id = 0)', $ids);

    $this->importTreeMemberProfile($ids, $idStr);
    $this->setPresetMemberProfiles();

    $this->conn->execute('DROP TABLE c_member_profile');
    $this->conn->execute('DROP TABLE c_profile');
    $this->conn->execute('DROP TABLE c_profile_option');
  }

  protected function importProfile($ids, $idStr)
  {
    $list = $this->conn->fetchAll('SELECT c_profile_id, name, is_required, public_flag_edit, public_flag_default, form_type, val_type, disp_regist, disp_config, disp_search, val_regexp, val_min, val_max, sort_order FROM c_profile WHERE c_profile_id IN ('.$idStr.') LIMIT 16', $ids);
    foreach ($list as $profile)
    {
      $valMin = (0 == $profile['val_min']) ? null : $profile['val_min'];
      $valMax = (0 == $profile['val_max']) ? null : $profile['val_max'];

      $this->conn->execute('INSERT INTO profile (id, name, is_required, is_unique, is_edit_public_flag, default_public_flag, form_type, value_type, is_disp_regist, is_disp_config, is_disp_search, value_regexp, value_min, value_max, sort_order, created_at, updated_at) VALUES (?, ?, ?, 0, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())', array(
        $profile['c_profile_id'] , $profile['name']     , $profile['is_required'] , $profile['public_flag_edit'] , $profile['public_flag_default'] ,
        $profile['form_type']    , $profile['val_type'] , $profile['disp_regist'] , $profile['disp_config']      , $profile['disp_search']         ,
        $profile['val_regexp']   , $valMin              , $valMax                 , $profile['sort_order']
      ));
    }
  }

  protected function importTreeMemberProfile($ids, $idStr)
  {
    $list = $this->conn->fetchColumn('SELECT c_profile_id FROM c_member_profile WHERE c_profile_id IN ('.$idStr.') AND c_profile_option_id <> 0', $ids);
    $list = array_unique($list);

    $baseSql = 'INSERT IGNORE INTO member_profile (id, member_id, profile_id, profile_option_id, value, value_datetime, public_flag, tree_key, lft, rgt, level, created_at, updated_at)';
    $rootId = $this->conn->fetchOne('SELECT MAX(c_member_profile_id) FROM c_member_profile') + 1;

    foreach ($list as $id)
    {
      $profileType = $this->conn->fetchOne('SELECT form_type FROM c_profile WHERE c_profile_id = ?', array($id));
      if ('checkbox' !== $profileType)
      {
        $this->conn->execute($baseSql.' (SELECT c_member_profile_id, c_member_id, c_profile_id, c_profile_option_id, NULL, NULL, public_flag, c_member_profile_id, 1, 2, 0, NOW(), NOW() FROM c_member_profile WHERE c_profile_id = ?)', array($id));
        continue;
      }

      $members = $this->conn->fetchColumn('SELECT c_member_id FROM c_member_profile WHERE c_profile_id = ?', array($id));
      $members = array_unique($members);
      foreach ($members as $i => $memberId)
      {
        $rootId++;
        $childs = $this->conn->fetchColumn('SELECT c_member_profile_id FROM c_member_profile WHERE c_profile_id = ? AND c_member_id = ?', array($id, $memberId));
        $this->conn->execute($baseSql.' (SELECT ?, c_member_id, c_profile_id, NULL, NULL, NULL, public_flag, ?, 1, ?, 0, NOW(), NOW() FROM c_member_profile WHERE c_profile_id = ? AND c_member_id = ? LIMIT 1)', array($rootId, $rootId, count($childs) * 2 + 2, $id, $memberId));

        foreach ($childs as $i => $child)
        {
          $num = $i + 1;

          $this->conn->execute($baseSql.' (SELECT c_member_profile_id, c_member_id, c_profile_id, c_profile_option_id, NULL, NULL, public_flag, ?, ?, ?, 1, NOW(), NOW() FROM c_member_profile WHERE c_member_profile_id = ? LIMIT 1)', array($rootId, $num * 2, $num * 2 + 1, $child));
        }
      }
    }
  }

  protected function setPresetMemberProfiles()
  {
    $this->conn->execute('INSERT INTO profile (id, name, is_required, is_unique, is_edit_public_flag, default_public_flag, form_type, value_type, is_disp_regist, is_disp_config, is_disp_search, value_regexp, value_min, value_max, sort_order, created_at, updated_at) (SELECT c_profile_id, ?, is_required, 0, public_flag_edit, public_flag_default, "textarea", "string", disp_regist, disp_config, disp_search, NULL, NULL, NULL, sort_order, NOW(), NOW() FROM c_profile WHERE name = ?)', array('op_preset_self_introduction', 'self_intro'));
    $introId = $this->conn->lastInsertId();
    $this->conn->execute('INSERT INTO member_profile (id, member_id, profile_id, profile_option_id, value, value_datetime, public_flag, tree_key, lft, rgt, level, created_at, updated_at) (SELECT c_member_profile_id, c_member_id, c_profile_id, NULL, value, NULL, public_flag, c_member_profile_id, 1, 2, 0, NOW(), NOW() FROM c_member_profile WHERE c_profile_id = ?)', array($introId));

    $this->conn->execute('INSERT INTO profile (id, name, is_required, is_unique, is_edit_public_flag, default_public_flag, form_type, value_type, is_disp_regist, is_disp_config, is_disp_search, value_regexp, value_min, value_max, sort_order, created_at, updated_at) VALUES (NULL, "op_preset_birthday", 1, 0, 0, 1, "date", "string", 1, 1, 1, NULL, NULL, NULL, 1, NOW(), NOW())');
    $birthdayId = $this->conn->lastInsertId();

    $date = $this->conn->expression->concat('birth_year', '"-"', 'birth_month', '"-"' , 'birth_day');
    $this->conn->execute('INSERT INTO member_profile (id, member_id, profile_id, profile_option_id, value, value_datetime, public_flag, tree_key, lft, rgt, level, created_at, updated_at) (SELECT NULL, c_member_id, ?, NULL, '.$date.', '.$date.', public_flag_birth_month_day, NULL, 1, 2, 0, NOW(), NOW() FROM c_member WHERE birth_year <> 0 AND birth_month <> 0 AND birth_day <> 0)', array($birthdayId));
    $this->conn->execute('UPDATE member_profile SET tree_key = id WHERE profile_id = ?', array($birthdayId));
  }
}
