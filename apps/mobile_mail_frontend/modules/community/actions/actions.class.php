<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * community actions.
 *
 * @package    OpenPNE
 * @subpackage community
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class communityActions extends sfActions
{
  public function executeImage(sfWebRequest $request)
  {
    $member = $this->getRoute()->getMember();
    if (!$member)
    {
      return sfView::NONE;
    }

    $community = Doctrine::getTable('Community')->find($request->getParameter('id'));
    if (!$community)
    {
      return sfView::ERROR;
    }

    $isAdmin = Doctrine::getTable('CommunityMember')->isAdmin($member->getId(), $community->getId());
    if (!$isAdmin || $community->getImageFileName())
    {
      return sfView::ERROR;
    }

    $message = $request->getMailMessage();
    if ($images = $message->getImages())
    {
      $image = array_shift($images);

      $validator = new opValidatorImageFile();
      $validFile = $validator->clean($image);

      $file = new File();
      $file->setFromValidatedFile($validFile);
      $file->setName('c_'.$community->getId().'_'.$file->getName());

      $community->setFile($file);
      $community->save();
    }

    return sfView::NONE;
  }
}
