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
      $mailAddress = $invite->getConfig('pc_address_pre');
      if (!$mailAddress)
      {
        $mailAddress = $invite->getConfig('mobile_address_pre');
      }

      $this->setWidget($invite->getId(), new sfWidgetFormInputCheckbox());
      $this->getWidget($invite->getId())->setLabel($mailAddress);
      $this->setValidator($invite->getId(), new sfValidatorBoolean());
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
        if (
          $key == $invite->getId()
          && $invite->getInviteMemberId() == sfContext::getInstance()->getUser()->getMemberId()
        )
        {
          $invite->delete();
        }
      }
    }

    return true;
  }
}
