<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opPDODatabaseSessionStorage
 *
 * @package    OpenPNE
 * @subpackage user
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opPDODatabaseSessionStorage extends sfPDOSessionStorage
{
  public function sessionOpen($path = null, $name = null)
  {
    if (is_string($this->options['database']))
    {
      $this->options['database'] = sfContext::getInstance()->getDatabaseManager()->getDatabase($this->options['database']);
    }

    return parent::sessionOpen($path, $name);
  }

  /**
   * Writes data to this storage replacing 4 byte utf8 characters.
   *
   * @param string $key   A unique key identifying your data
   * @param mixed  $data  Data associated with your key
   *
   * @see sfSessionStorage
   */
  public function write($key, $data)
  {
    // "utf8", a type of character set in MySQL, can't handle 4 bytes utf8 characters
    // so we replace such a character to "U+FFFD" (A unicode "REPLACEMENT CHARACTER").
    if (!$this->isReadyFor4BytesUtf8())
    {
      $data = $this->replace4BytesUtf8Characters($data);
    }

    parent::write($key, $data);
  }

  protected function replace4BytesUtf8Characters($value)
  {
    if (is_array($value))
    {
      $result = array();
      foreach ($value as $k => $v)
      {
        $result[$this->replace4BytesUtf8Characters($k)] = $this->replace4BytesUtf8Characters($v);
      }

      return $result;
    }
    elseif (!is_string($value))
    {
      return $value;
    }

    // See: RFC 3629 (section 4, Syntax of UTF-8 Byte Sequences)
    // http://tools.ietf.org/html/rfc3629#section-4
    return preg_replace('/(
      \xF0[\x90-\xBF][\x80-\xBF]{2}| # %xF0 %x90-BF 2( UTF8-tail )
      [\xF1-\xF3][\x80-\xBF]{3}|     # %xF1-F3 3( UTF8-tail )
      \xF4[\x80-\x8F][\x80-\xBF]{2}  # %xF4 %x80-8F 2( UTF8-tail )
    )/x', "\xEF\xBF\xBD", $value);
  }

  protected function isReadyFor4BytesUtf8()
  {
    return 'mysql' !== $this->db->getAttribute(PDO::ATTR_DRIVER_NAME);
  }
}
