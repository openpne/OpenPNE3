<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * Invitelist form.
 *
 * @package    OpenPNE
 * @subpackage form
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class InvitelistForm extends BaseForm
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
