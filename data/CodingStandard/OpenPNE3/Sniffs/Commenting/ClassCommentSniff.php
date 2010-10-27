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
  public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
  {
    if (
      // myUser.class.php should be excluded
      false !== strpos($phpcsFile->getFilename(), 'myUser.class.php')
    )
    {
      return null;
    }

    $this->currentFile = $phpcsFile;

    $tokens = $phpcsFile->getTokens();
    $find   = array (T_ABSTRACT, T_WHITESPACE, T_FINAL);

    // Extract the class comment docblock.
    $commentEnd = $phpcsFile->findPrevious($find, ($stackPtr - 1), null, true);

    if ($commentEnd !== false && $tokens[$commentEnd]['code'] === T_COMMENT)
    {
      $phpcsFile->addError('You must use "/**" style comments for a class comment', $stackPtr);

      return;
    } else if ($commentEnd === false || $tokens[$commentEnd]['code'] !== T_DOC_COMMENT)
    {
      $phpcsFile->addError('Missing class doc comment', $stackPtr);

      return;
    }

    $commentStart = ($phpcsFile->findPrevious(T_DOC_COMMENT, ($commentEnd - 1), null, true) + 1);
    $commentNext  = $phpcsFile->findPrevious(T_WHITESPACE, ($commentEnd + 1), $stackPtr, false, $phpcsFile->eolChar);

    // Distinguish file and class comment.
    $prevClassToken = $phpcsFile->findPrevious(T_CLASS, ($stackPtr - 1));
    if ($prevClassToken === false)
    {
      // This is the first class token in this file, need extra checks.
      $prevNonComment = $phpcsFile->findPrevious(T_DOC_COMMENT, ($commentStart - 1), null, true);
      if ($prevNonComment !== false)
      {
        $prevComment = $phpcsFile->findPrevious(T_DOC_COMMENT, ($prevNonComment - 1));
        if ($prevComment === false)
        {
          // There is only 1 doc comment between open tag and class token.
          $newlineToken = $phpcsFile->findNext(T_WHITESPACE, ($commentEnd + 1), $stackPtr, false, $phpcsFile->eolChar);
          if ($newlineToken !== false)
          {
            $newlineToken = $phpcsFile->findNext(T_WHITESPACE, ($newlineToken + 1), $stackPtr, false, $phpcsFile->eolChar);
            if ($newlineToken !== false)
            {
              // Blank line between the class and the doc block.
              // The doc block is most likely a file comment.
              $phpcsFile->addError('Missing class doc comment', ($stackPtr + 1));

              return;
            }
          }//end if
        }//end if

        // Exactly one blank line before the class comment.
        $prevTokenEnd = $phpcsFile->findPrevious(T_WHITESPACE, ($commentStart - 1), null, true);
        if ($prevTokenEnd !== false)
        {
          $blankLineBefore = 0;
          for ($i = ($prevTokenEnd + 1); $i < $commentStart; $i++)
          {
            if ($tokens[$i]['code'] === T_WHITESPACE && $tokens[$i]['content'] === $phpcsFile->eolChar)
            {
              $blankLineBefore++;
            }
          }

          if ($blankLineBefore !== 2)
          {
            $error = 'There must be exactly one blank line before the class comment';
            $phpcsFile->addError($error, ($commentStart - 1));
          }
        }
      }//end if
    }//end if

    $commentString = $phpcsFile->getTokensAsString($commentStart, ($commentEnd - $commentStart + 1));

    // Parse the class comment docblock.
    try
    {
      $this->commentParser = new PHP_CodeSniffer_CommentParser_ClassCommentParser($commentString, $phpcsFile);
      $this->commentParser->parse();
    }
    catch (PHP_CodeSniffer_CommentParser_ParserException $e)
    {
      $line = ($e->getLineWithinComment() + $commentStart);
      $phpcsFile->addError($e->getMessage(), $line);

      return;
    }

    $comment = $this->commentParser->getComment();
    if (is_null($comment) === true)
    {
      $error = 'Class doc comment is empty';
      $phpcsFile->addError($error, $commentStart);

      return;
    }

    // The first line of the comment should just be the /** code.
    $eolPos    = strpos($commentString, $phpcsFile->eolChar);
    $firstLine = substr($commentString, 0, $eolPos);
    if ($firstLine !== '/**')
    {
      $error = 'The open comment tag must be the only content on the line';
      $phpcsFile->addError($error, $commentStart);
    }

    // Check for a comment description.
    $short = rtrim($comment->getShortComment(), $phpcsFile->eolChar);
    if (trim($short) === '')
    {
      $error = 'Missing short description in class doc comment';
      $phpcsFile->addError($error, $commentStart);

      return;
    }

    // No extra newline before short description.
    $newlineCount = 0;
    $newlineSpan  = strspn($short, $phpcsFile->eolChar);
    if ($short !== '' && $newlineSpan > 0)
    {
      $line  = ($newlineSpan > 1) ? 'newlines' : 'newline';
      $error = "Extra $line found before class comment short description";
      $phpcsFile->addError($error, ($commentStart + 1));
    }

    // Exactly one blank line before tags.
    $tags = $this->commentParser->getTagOrders();
    if (count($tags) > 1)
    {
      $newlineSpan = $comment->getNewlineAfter();
      if ($newlineSpan !== 2)
      {
        $error = 'There must be exactly one blank line before the tags in class comment';
        if ($long !== '')
        {
          $newlineCount += (substr_count($long, $phpcsFile->eolChar) - $newlineSpan + 1);
        }

        $phpcsFile->addError($error, ($commentStart + $newlineCount));
        $short = rtrim($short, $phpcsFile->eolChar.' ');
      }
    }

    // Check for unknown/deprecated tags.
    $unknownTags = $this->commentParser->getUnknown();
    foreach ($unknownTags as $errorTag)
    {
      $error = "@$errorTag[tag] tag is not allowed in class comment";
      $phpcsFile->addWarning($error, ($commentStart + $errorTag['line']));

      return;
    }

    // Check each tag.
    $this->processTags($commentStart, $commentEnd);
  }

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
