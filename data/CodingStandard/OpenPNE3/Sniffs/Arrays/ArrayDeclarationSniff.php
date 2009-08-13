<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * OpenPNE3_Sniffs_Arrays_ArrayDeclarationSniff
 *
 * @package    OpenPNE
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class OpenPNE3_Sniffs_Arrays_ArrayDeclarationSniff implements PHP_CodeSniffer_Sniff
{
  public function register()
  {
    return array(T_ARRAY);
  }

  public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
  {
    $tokens = $phpcsFile->getTokens();

    $arrayStart = $tokens[$stackPtr]['parenthesis_opener'];
    $arrayEnd = $tokens[$arrayStart]['parenthesis_closer'];
    $keywordStart = $tokens[$stackPtr]['column'];

    $baseIndent = $arrayStart;
    $firstChar = $arrayStart;
    while ($firstChar = $phpcsFile->findPrevious(T_WHITESPACE, $firstChar - 1, null, true, null, true))
    {
      if (T_EQUAL == $tokens[$firstChar]['code'])
      {
        $varChar = $phpcsFile->findPrevious(T_VARIABLE, $firstChar - 1, null, false, null, true);
        $baseIndent = $tokens[$varChar]['column'];

        break;
      }
    }

    // single line array
    if ($tokens[$arrayStart]['line'] === $tokens[$arrayEnd]['line'])
    {
      $nextArrow = $arrayStart;
      while (($nextArrow = $phpcsFile->findNext(T_DOUBLE_ARROW, ($nextArrow + 1), $arrayEnd)) !== false)
      {
        if ($tokens[($nextArrow - 1)]['code'] !== T_WHITESPACE)
        {
          $content = $tokens[($nextArrow - 1)]['content'];
          $error = "Expected 1 space between \"$content\" and double arrow; 0 found";
          $phpcsFile->addError($error, $nextArrow);
        }
        else
        {
          $spaceLength = strlen($tokens[($nextArrow - 1)]['content']);
          if ($spaceLength !== 1)
          {
            $content = $tokens[($nextArrow - 2)]['content'];
            $error = "Expected 1 space between \"$content\" and double arrow; $spaceLength found";
            $phpcsFile->addError($error, $nextArrow);
          }
        }

        if ($tokens[($nextArrow + 1)]['code'] !== T_WHITESPACE)
        {
          $content = $tokens[($nextArrow + 1)]['content'];
          $error = "Expected 1 space between double arrow and \"$content\"; 0 found";
          $phpcsFile->addError($error, $nextArrow);
        }
        else
        {
          $spaceLength = strlen($tokens[($nextArrow + 1)]['content']);
          if ($spaceLength !== 1)
          {
            $content = $tokens[($nextArrow + 2)]['content'];
            $error = "Expected 1 space between double arrow and \"$content\"; $spaceLength found";
            $phpcsFile->addError($error, $nextArrow);
          }
        }
      }

      return;
    }

    // Check the closing bracket is on a new line.
    $lastContent = $phpcsFile->findPrevious(array(T_WHITESPACE), ($arrayEnd - 1), $arrayStart, true);
    if ($tokens[$lastContent]['line'] !== ($tokens[$arrayEnd]['line'] - 1)) 
    {
      $error = 'Closing parenthesis of array declaration must be on a new line';
      $phpcsFile->addError($error, $arrayEnd);
    }
    elseif ($tokens[$arrayEnd]['column'] !== $baseIndent)
    {
      // Check the closing bracket is lined up under the a in array.
      $expected = $baseIndent;
      $expected .= ($baseIndent === 0) ? ' space' : ' spaces';
      $found = $tokens[$arrayEnd]['column'];
      $found .= ($found === 0) ? ' space' : ' spaces';
      $phpcsFile->addError("Closing parenthesis not aligned correctly; expected $expected but found $found", $arrayEnd);
    }

    $nextToken = $stackPtr;
    $lastComma = $stackPtr;
    $keyUsed = false;
    $singleUsed = false;
    $lastToken = '';
    $indices = array();
    $maxLength = 0;

    while (($nextToken = $phpcsFile->findNext(array(T_DOUBLE_ARROW, T_COMMA, T_ARRAY), ($nextToken + 1), $arrayEnd)) !== false)
    {
      $currentEntry = array();

      if ($tokens[$nextToken]['code'] === T_ARRAY)
      {
        $indices[] = array('value' => $nextToken);
        $nextToken = $tokens[$tokens[$nextToken]['parenthesis_opener']]['parenthesis_closer'];
        continue;
      }

      if ($tokens[$nextToken]['code'] === T_COMMA)
      {
        $stackPtrCount = 0;
        if (isset($tokens[$stackPtr]['nested_parenthesis']) === true)
        {
          $stackPtrCount = count($tokens[$stackPtr]['nested_parenthesis']);
        }

        if (count($tokens[$nextToken]['nested_parenthesis']) > ($stackPtrCount + 1))
        {
          continue;
        }

        if ($tokens[($nextToken - 1)]['code'] === T_WHITESPACE)
        {
          $content = $tokens[($nextToken - 2)]['content'];
          $spaceLength = strlen($tokens[($nextToken - 1)]['content']);
          $error = "Expected 0 spaces between \"$content\" and comma; $spaceLength found";
          $phpcsFile->addWarning($error, $nextToken);
        }

        $valueContent = $phpcsFile->findPrevious(PHP_CodeSniffer_Tokens::$emptyTokens, ($nextToken - 1), null, true);
        while ($tokens[$valueContent]['line'] === $tokens[$nextToken]['line']) 
        {
          if ($valueContent === $arrayStart)
          {
            break;
          }

          $valueContent--;
        }

        $valueContent = $phpcsFile->findNext(T_WHITESPACE, ($valueContent + 1), $nextToken, true);
        $indices[]    = array('value' => $valueContent);
        $singleUsed   = true;
      }

      $lastToken = T_COMMA;
      continue;
    }

    if ($tokens[$nextToken]['code'] === T_DOUBLE_ARROW)
    {
      if ($singleUsed === true)
      {
        $error = 'Key specified for array entry; first entry has no key';
        $phpcsFile->addError($error, $nextToken);
        return;
      }

      $currentEntry['arrow'] = $nextToken;
      $keyUsed               = true;

      $indexEnd   = $phpcsFile->findPrevious(T_WHITESPACE, ($nextToken - 1), $arrayStart, true);
      $indexStart = $phpcsFile->findPrevious(T_WHITESPACE, $indexEnd, $arrayStart);

      if ($indexStart === false)
      {
        $index = $indexEnd;
      }
      else
      {
        $index = ($indexStart + 1);
      }

      $currentEntry['index']         = $index;
      $currentEntry['index_content'] = $phpcsFile->getTokensAsString($index, ($indexEnd - $index + 1));

      $indexLength = strlen($currentEntry['index_content']);
      if ($maxLength < $indexLength)
      {
        $maxLength = $indexLength;
      }

      // Find the value of this index.
      $nextContent = $phpcsFile->findNext(array(T_WHITESPACE), ($nextToken + 1), $arrayEnd, true);
      $currentEntry['value'] = $nextContent;
      $indices[] = $currentEntry;
      $lastToken = T_DOUBLE_ARROW;
    }

    // Check for mutli-line arrays that should be single-line.
    $singleValue = false;

    if (empty($indices) === true)
    {
      $singleValue = true;
    }
    elseif (count($indices) === 1)
    {
      if ($lastToken === T_COMMA)
      {
        // There may be another array value without a comma.
        $exclude = PHP_CodeSniffer_Tokens::$emptyTokens;
        $exclude[] = T_COMMA;
        $nextContent = $phpcsFile->findNext($exclude, ($indices[0]['value'] + 1), $arrayEnd, true);
        if ($nextContent === false)
        {
          $singleValue = true;
        }
      }

      if ($singleValue === false && isset($indices[0]['arrow']) === false)
      {
        // A single nested array as a value is fine.
        if ($tokens[$indices[0]['value']]['code'] !== T_ARRAY)
        {
          $singleValue === true;
        }
      }
    }

    if ($singleValue === true)
    {
      $error = 'Multi-line array contains a single value; use single-line array instead';
      $phpcsFile->addError($error, $stackPtr);
      return;
    }

    if ($keyUsed === false && empty($indices) === false)
    {
      $count     = count($indices);
      $lastIndex = $indices[($count - 1)]['value'];

      $trailingContent = $phpcsFile->findPrevious(T_WHITESPACE, ($arrayEnd - 1), $lastIndex, true);
      if ($tokens[$trailingContent]['code'] !== T_COMMA)
      {
        $error = 'Comma required after last value in array declaration';
        $phpcsFile->addError($error, $trailingContent);
      }
    }

    $numValues = count($indices);

    $indicesStart = ($keywordStart + 1);
    $arrowStart   = ($indicesStart + $maxLength + 1);
    $valueStart   = ($arrowStart + 3);
    foreach ($indices as $index)
    {
      if (isset($index['index']) === false)
      {
        if (($tokens[$index['value']]['line'] === $tokens[$stackPtr]['line']) && ($numValues > 1))
        {
          $phpcsFile->addError('The first value in a multi-value array must be on a new line', $stackPtr);
        }

        continue;
      }

      if ($tokens[$index['value']]['code'] !== T_ARRAY)
      {
        $nextComma = $phpcsFile->findNext(array(T_COMMA), ($index['value'] + 1));
        if (($nextComma === false) || ($tokens[$nextComma]['line'] !== $tokens[$index['value']]['line']))
        {
          $error = 'Each line in an array declaration must end in a comma';
          $phpcsFile->addError($error, $index['value']);
        }

        if ($nextComma !== false && $tokens[($nextComma - 1)]['code'] === T_WHITESPACE)
        {
          $content     = $tokens[($nextComma - 2)]['content'];
          $spaceLength = strlen($tokens[($nextComma - 1)]['content']);
          $error       = "Expected 0 spaces between \"$content\" and comma; $spaceLength found";
          $phpcsFile->addError($error, $nextComma);
        }
      }
    }
  }
}
