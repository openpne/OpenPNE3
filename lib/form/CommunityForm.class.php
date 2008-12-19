<?php

/**
 * Community form.
 *
 * @package    OpenPNE
 * @subpackage form
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class CommunityForm extends BaseCommunityForm
{
  public function configure()
  {
    $this->setWidget('file', new sfWidgetFormInputFile());

    unset($this['created_at'], $this['updated_at'], $this['file_id']);

    $this->setValidator('config', new sfValidatorPass());
    $this->setValidator('file', new opValidatorImageFile(array('required' => false)));
  }

  public function save($con = null)
  {
    $community = parent::save($con);
    $oldFile = $community->getFile();
    $this->saveImageFile($community);
    $community->save();

    if ($oldFile && $oldFile->getName() !== $community->getFile()->getName())
    {
      $oldFile->delete();
    }

    return $community;
  }

  public function updateObject($values = null)
  {
    $object = parent::updateObject($values);

    $this->saveMember($object);
    $this->saveConfig($object);

    return $object;
  }

  public function saveConfig(Community $community)
  {
    $configs = $this->getValue('config');
    if (!$configs)
    {
      return false;
    }

    foreach ($configs as $key => $value)
    {
      $config = CommunityConfigPeer::retrieveByNameAndCommunityId($key, $community->getId());
      if (!$config)
      {
        $config->setCommunity($community);
        $config->setName($key);
      }
      $config->setValue($value);
    }
  }

  public function saveMember(Community $community)
  {
    if ($this->isNew())
    {
      $member = new CommunityMember();
      $member->setPosition('admin');
      $member->setMemberId(sfContext::getInstance()->getUser()->getMemberId());
      $member->setCommunity($community);
    }
  }

  public function saveImageFile(Community $community)
  {
    if (!$this->getValue('file'))
    {
      return false;
    }

    $file = new File();
    $file->setFromValidatedFile($this->getValue('file'));
    $file->setName('c_'.$community->getId().'_'.$file->getName());
    $community->setFile($file);

    return true;
  }
}
