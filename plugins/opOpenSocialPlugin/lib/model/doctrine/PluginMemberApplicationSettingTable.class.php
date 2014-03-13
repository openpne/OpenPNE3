<?php
/**
 */
class PluginMemberApplicationSettingTable extends Doctrine_Table
{
  public function set($memberApplication, $type, $name, $value = null)
  {
    $setting = $this->createQuery()
      ->where('member_application_id = ?', $memberApplication->getId())
      ->andWhere('type = ?', $type)
      ->andWhere('name = ?', $name)
      ->fetchOne();
    if (!$setting)
    {
      $setting = new MemberApplicationSetting();
      $setting->setMemberApplication($memberApplication);
      $setting->setType($type);
      $setting->setName($name);
    }
    $setting->setValue($value);
    $setting->save();
    return $setting;
  }
}
