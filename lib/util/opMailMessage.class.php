<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opMailMessage
 *
 * @package    OpenPNE
 * @subpackage util
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opMailMessage extends Zend_Mail_Message
{
  protected $firstTextPos = 0;

  public function getContent()
  {
    // If this message is multi-part-mail,
    // opMailMessage retrives first text/plain part as a content
    if ($this->isMultiPart())
    {
      if ($this->firstTextPos)
      {
        return $this->getPart($this->firstTextPos)->getContent();
      }

      $content = '';
      $current = $this->key();
      $this->rewind();

      foreach ($this as $part)
      {
        $tok = strtok($part->contentType, ';');
        if ('text/plain' === $tok)
        {
          $this->firstTextPos = $this->key();
          $content = mb_convert_encoding($this->current()->getContent(), 'UTF-8', 'JIS');

          break;
        }
      }

      $this->_iterationPos = $current;

      return $content;
    }
    elseif ('text/plain' === strtok($this->getPart(0)->contentType, ';'))
    {
      return mb_convert_encoding(parent::getContent(), 'UTF-8', 'JIS');
    }
    else
    {
      return '';
    }
  }

  private static function saveBase64ImageInTempDir($content)
  {
    $tmppath = tempnam(sys_get_temp_dir(), 'IMG');

    $fh = fopen($tmppath, 'w');
    fwrite($fh, base64_decode(rtrim($content), true));
    fclose($fh);

    return $tmppath;
  }

  public function getImages()
  {
    $images = array();
    $allowTypes = array('image/jpeg', 'image/png', 'image/gif');

    if (!$this->isMultiPart())
    {
      $type = strtok($this->getPart(0)->contentType, ';');
      if (in_array($type, $allowTypes))
      {
        $image[] = array(
          'tmp_name' => self::saveBase64ImageInTempDir(parent::getContent()),
          'type'     => $type,
        );
      }

      return $images;
    }

    $current = $this->key();
    $this->rewind();

    foreach ($this as $part)
    {
      $tok = strtok($part->contentType, ';');
      if (in_array($tok, $allowTypes))
      {
        $images[] = array(
          'tmp_name' => self::saveBase64ImageInTempDir($part->getContent()),
          'type'    => $tok,
        );
      }
    }

    $this->_iterationPos = $current;

    return $images;
  }

  public function getHeader($name, $format = null)
  {
    $result = parent::getHeader($name, $format);

    if ('array' !== $format && function_exists('mb_decode_mimeheader'))
    {
      $result = mb_decode_mimeheader($result);
    }

    if ('from' === strtolower($name) || 'to' === strtolower($name))
    {
      $result = $this->extractAddrSpec($result);
    }

    return $result;
  }

  protected function extractAddrSpec($mailAddress)
  {
    // stripped double-quotes (")
    $mailAddress = str_replace('"', '', $mailAddress);

    // extract addr-spec
    $matches = array();
    $regx = '/([\.\w!#$%&\'*+\-\/=?^`{|}~]+@[\w!#$%&\'*+\-\/=?^`{|}~]+(\.[\w!#$%&\'*+\-\/=?^`{|}~]+)*)/';
    if (preg_match_all($regx, $mailAddress, $matches))
    {
      return array_pop($matches[1]);
    }

    return '';
  }
}
