<?php

/**
 * Invitelist form.
 *
 * @package    OpenPNE
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfPropelFormTemplate.php 10377 2008-07-21 07:10:32Z dwhittle $
 */
class InvitelistForm extends sfForm
{
  public function configure()
  {
    foreach ($this->getOption('invites') as $invite)
    {
      $member = MemberPeer::retrieveByPk($invite->getMemberIdTo());
      $name = strval($invite->getMemberIdTo());
      $this->setWidget($name, new sfWidgetFormInputCheckbox());
      $this->getWidget($name)->setLabel($invite->getMailAddress());
      $this->setValidator($name, new sfValidatorBoolean());
    }
    
    $this->widgetSchema->setNameFormat('invitelist[%s]');
  }
  
  public function save()
  {
    if (!$this->isValid())
    {
      return false;
    }

    $invites = $this->getOption('invites');
    foreach ($this->values as $key => $value)
    {
      if (!$value)
      {
        continue;
      }
      foreach ($invites as $invite)
      {
        if ($invite->getMemberIdTo() == $key)
        {
          $invite->deleteRelation();
          $invite->delete();
          break;
        }
      }
    }

    return true;
  }
}
