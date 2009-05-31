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

    foreach ($request->getMailMessage() as $part)
    {
      $count = $member->getMemberImage()->count();
      if ($count >= 3)
      {
        throw new opRuntimeException('Cannot add an image any more.');
      }

      $tok = strtok($part->contentType, ';');
      if ('text/plain' === $tok)
      {
        continue;
      }

      $tmppath = tempnam(sys_get_temp_dir(), 'IMG');

      $fh = fopen($tmppath, 'w');
      fwrite($fh, base64_decode($part->getContent(), true));
      fclose($fh);

      $validator = new opValidatorImageFile();
      $validFile = $validator->clean(array(
        'tmp_name' => $tmppath,
        'type'     => $tok,
      ));

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
  }
}
