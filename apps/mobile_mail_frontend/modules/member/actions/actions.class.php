<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * member actions.
 *
 * @package    OpenPNE
 * @subpackage member
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class memberActions extends sfActions
{
  public function executeImage(sfWebRequest $request)
  {
    $member = $this->getRoute()->getMember();
    if (!$member)
    {
      return sfView::NONE;
    }

    $message = $request->getMailMessage();
    $images = $message->getImages();
    foreach ($images as $image)
    {
      $count = $member->getMemberImage()->count();
      if ($count >= 3)
      {
        return sfView::ERROR;
      }

      $validator = new opValidatorImageFile();
      $validFile = $validator->clean($image);

      $file = new File();
      $file->setFromValidatedFile($validFile);
      $file->setName('m_'.$member->getId().'_'.$file->getName());

      $memberImage = new MemberImage();
      $memberImage->setMember($member);
      $memberImage->setFile($file);
      if (!$count)
      {
        $memberImage->setIsPrimary(true);
      }

      $memberImage->save();
    }

    return sfView::NONE;
  }
}
