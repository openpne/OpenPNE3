<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * sfMobileIOFilter
 *
 * @package    OpenPNE
 * @subpackage filter
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class sfMobileIOFilter extends sfFilter
{
  /**
   * Executes this filter.
   *
   * @param sfFilterChain $filterChain A sfFilterChain instance
   */
  public function execute($filterChain)
  {
    $this->convertEmojiForInput();
    $this->convertEncodingForInput();

    $filterChain->execute();

    $this->convertEmptyElementsForHTML4();
    $this->convertEncodingForOutput();
    $this->outputContentTypeHeader();
  }

  /**
   * Outputs a Content-type header.
   */
  private function outputContentTypeHeader()
  {
    header('Content-Type: text/html; charset=Shift_JIS');
  }

  /**
   * Converts character encoding for input.
   */
  private function convertEncodingForInput()
  {
    $request = $this->getContext()->getRequest();
    $parameter_holder = $request->getParameterHolder();

    foreach ($parameter_holder->getAll() as $key => $value) {
      $parameter_holder->set($key, $this->convertEncodingForInputCallback($value));
    }
  }

  private function convertEncodingForInputCallback($value)
  {
    if (is_array($value)) {
      return array_map(array($this, 'convertEncodingForInputCallback'), $value);
    }

    return mb_convert_encoding($value, 'UTF-8', 'SJIS-win');
  }

  private function convertEmojiForInput()
  {
    $request = $this->getContext()->getRequest();
    $parameter_holder = $request->getParameterHolder();

    foreach ($parameter_holder->getAll() as $key => $value) {
      $parameter_holder->set($key, $this->convertEmojiForInputCallback($value));
    }
  }

  private function convertEmojiForInputCallback($value)
  {
    if (is_array($value))
    {
      return array_map(array($this, 'convertEmojiForInputCallback'), $value);
    }

    $result = '';

    for ($i = 0; $i < strlen($value); $i++)
    {
      $emoji = '';
      $c1 = ord($value[$i]);
      if ($this->getContext()->getRequest()->getMobile()->isSoftBank())
      {
        if ($c1 == 0xF7 || $c1 == 0xF9 || $c1 == 0xFB)
        {
          $bin = substr($value, $i, 2);
          $emoji = OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat($bin);
        }
      } 
      elseif ($c1 == 0xF8 || $c1 == 0xF9)
      {
        $bin = substr($value, $i, 2);
        $emoji = OpenPNE_KtaiEmoji::convertDoCoMoEmojiToOpenPNEFormat($bin);
      }
      elseif (0xF3 <= $c1 && $c1 <= 0xF7)
      {
        $bin = substr($value, $i, 2);
        $emoji = OpenPNE_KtaiEmoji::convertEZWebEmojiToOpenPNEFormat($bin);
      }
      if ($emoji)
      {
        $result .= $emoji;
        $i++;
      }
      else
      {
        $result .= $value[$i];
        if ((0x81 <= $c1 && $c1 <= 0x9F) || 0xE0 <= $c1)
        {
          $result .= $value[$i+1];
          $i++;
        }
      }
    }

    return $result;
  }

  /**
   * Converts character encoding for output.
   */
  private function convertEncodingForOutput()
  {
    $response = $this->getContext()->getResponse();
    $response->setContent(mb_convert_encoding($response->getContent(), 'SJIS-win', 'UTF-8'));
  }

  private function convertEmptyElementsForHTML4()
  {
    $response = $this->getContext()->getResponse();
    $response->setContent(str_replace('/>', '>', $response->getContent()));
  }
}
