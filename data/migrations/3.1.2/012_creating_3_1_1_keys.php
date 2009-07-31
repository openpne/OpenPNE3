<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class creating311Keys extends Doctrine_Migration_Base
{
  public function migrate($direction)
  {
    $conn = Doctrine_Manager::connection();
    $list = $conn->import->listTableConstraints('banner_translation');
    if ($direction == 'up' && $list)
    {
      return null;
    }

    $this->foreignkey($direction, 'banner_translation', 'banner_translation_id_banner_id', array(
      'name'         => 'banner_translation_id_banner_id',
      'local'        => 'id',
      'foreign'      => 'id',
      'foreignTable' => 'banner',
      'onUpdate'     => 'CASCADE',
      'onDelete'     => 'CASCADE',
    ));

    $this->foreignkey($direction, 'banner_image', 'banner_image_file_id_file_id', array(
      'name'         => 'banner_image_file_id_file_id',
      'local'        => 'file_id',
      'foreign'      => 'id',
      'foreignTable' => 'file',
      'onUpdate'     => '',
      'onDelete'     => 'cascade',
    ));

    $this->foreignkey($direction, 'banner_use_image', 'banner_use_image_banner_id_banner_id', array(
      'name'         => 'banner_use_image_banner_id_banner_id',
      'local'        => 'banner_id',
      'foreign'      => 'id',
      'foreignTable' => 'banner',
    ));

    $this->foreignkey($direction, 'banner_use_image', 'banner_use_image_banner_image_id_banner_image_id', array(
      'name'         => 'banner_use_image_banner_image_id_banner_image_id',
      'local'        => 'banner_image_id',
      'foreign'      => 'id',
      'foreignTable' => 'banner_image',
    ));

    $this->foreignkey($direction, 'o_auth_admin_token', 'o_auth_admin_token_admin_user_id_admin_user_id', array(
      'name'         => 'o_auth_admin_token_admin_user_id_admin_user_id',
      'local'        => 'admin_user_id',
      'foreign'      => 'id',
      'foreignTable' => 'admin_user',
      'onUpdate'     => '',
      'onDelete'     => 'cascade',
    ));

    $this->foreignkey($direction, 'o_auth_admin_token', 'o_auth_admin_token_oauth_consumer_id_oauth_consumer_id', array(
      'name'         => 'o_auth_admin_token_oauth_consumer_id_oauth_consumer_id',
      'local'        => 'oauth_consumer_id',
      'foreign'      => 'id',
      'foreignTable' => 'oauth_consumer',
      'onUpdate'     => '',
      'onDelete'     => 'cascade',
    ));

    $this->foreignkey($direction, 'oauth_consumer', 'oauth_consumer_file_id_file_id', array(
      'name'         => 'oauth_consumer_file_id_file_id',
      'local'        => 'file_id',
      'foreign'      => 'id',
      'foreignTable' => 'file',
      'onUpdate'     => '',
      'onDelete'     => 'set null',
    ));
  }
}
