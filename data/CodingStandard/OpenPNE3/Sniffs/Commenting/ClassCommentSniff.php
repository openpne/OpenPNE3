<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * OpenPNE3_Sniffs_Commenting_ClassCommentSniff
 *
 * @package    OpenPNE
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class OpenPNE3_Sniffs_Commenting_ClassCommentSniff extends Squiz_Sniffs_Commenting_ClassCommentSniff
{
  protected function processTags($commentStart, $commentEnd)
  {
    $foundTags = $this->commentParser->getTagOrders();

    if (!in_array('author', $foundTags))
    {
      $error = 'Missing @author tag in class comment';
      $this->currentFile->addError($error, $commentEnd);
    }
    else
    {
      $this->parseAuthor($commentStart);
    }

    if (!in_array('package', $foundTags))
    {
      $error = 'Missing @package tag in class comment';
      $this->currentFile->addError($error, $commentEnd);
    }
    else
    {
      $package = $this->commentParser->getPackage();

      if ('OpenPNE' !== $package->getContent()
          && !preg_match('/^op.+Plugin$/', $package->getContent())
      )
      {
        $error = '@package must be "OpenPNE" or plugin name.';
        $this->currentFile->addError($error, $commentEnd);
      }
    }
  }

  protected function parseAuthor($commentStart)
  {
    $authors = $this->commentParser->getAuthors();

    foreach ($authors as $author)
    {
      if (in_array($author->getContent(), array('Your name here', '##NAME## <##EMAIL##>')))
      {
        $errorPos = ($commentStart + $author->getLine());
        $error = '@author is a default value, "Your name here" or "##NAME##".';
        $this->currentFile->addError($error, $errorPos);
      }
    }
  }

  protected function parseArray($errorPos)
  {
    $authors = $this->commentParser->getAuthors();

    foreach ($authors as $author)
    {
      if (in_array($author->getContent(), array('Your name here', '##NAME## <##EMAIL##>')))
      {
        $error = '@author is a default value, "Your name here" or "##NAME##".';
        $this->currentFile->addError($error, $errorPos);
      }
    }
  }
}
