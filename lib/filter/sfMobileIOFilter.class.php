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
    // This filter should affect only real mobile phone
    if (sfConfig::get('sf_environment') === 'test')
    {
      $filterChain->execute();

      return null;
    }

    $this->convertEmojiForInput();
    $this->convertEncodingForInput();

    $filterChain->execute();

    $this->convertEmptyElementsForHTML4();
    if ($this->getContext()->getRequest()->getMobile()->isDoCoMo() && opConfig::get('font_size'))
    {
      $this->convertAddFont4Docomo();
    }
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
    sfContext::getInstance()->getRequest()->convertEncodingForInput('SJIS-win');
  }

  private function convertEmojiForInput()
  {
    $request = $this->getContext()->getRequest();
    $parameter_holder = $request->getParameterHolder();

    foreach ($parameter_holder->getAll(false) as $key => $value) {
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

  private function convertAddFont4Docomo()
  {
    $response = $this->getContext()->getResponse();
    $content = $response->getContent();
    $pattern_start_tag = array('/(<body.*?>)/', '/(<td.*?>)/');
    $replacement_start_tag = '$1<font size="2">';
    $pattern_end_tag = array('</body>', '</td>');
    $replacement_end_tag = array('</font></body>', '</font></td>');

    $content = preg_replace($pattern_start_tag, $replacement_start_tag, $content);
    $content = str_replace($pattern_end_tag, $replacement_end_tag, $content);
    $response->setContent($content);
  }
}
