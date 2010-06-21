<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class Revision44_AddActivityTerm extends Doctrine_Migration_Base
{
  protected function setTerm($name, $application, $values)
  {
    $object = Doctrine::getTable('SnsTerm')->findOneByNameAndApplication($name, $application);
    if (!$object)
    {
      $object = new SnsTerm();
      $object->name = $name;
      $object->application = $application;
    }
    foreach ($values as $key => $value)
    {
      if (!$object->Translation[$key]->value)
      {
        $object->Translation[$key]->value = $value;
      }
    }
    $object->save();
  }

  public function up()
  {
    $this->setTerm('activity', 'pc_frontend', array(
      'ja_JP' => 'アクティビティ',
      'en' => 'activity'
    ));

    $this->setTerm('activity', 'mobile_frontend', array(
      'ja_JP' => 'ｱｸﾃｨﾋﾞﾃｨ',
      'en' => 'activity'
    ));

    $this->setTerm('post_activity', 'pc_frontend', array(
      'ja_JP' => 'アクティビティ投稿',
      'en' => 'Post Activity'
    ));

    $this->setTerm('post_activity', 'mobile_frontend', array(
      'ja_JP' => '投稿',
      'en' => 'Post Activity'
    ));
  }
}
