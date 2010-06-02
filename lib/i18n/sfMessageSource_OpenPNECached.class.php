<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * sfMessageSource_OpenPNECached
 *
 * @package    OpenPNE
 * @subpackage i18n
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class sfMessageSource_OpenPNECached extends sfMessageSource_File
{
  protected $dataExt = '.xml.php';

  public function getCatalogueList($catalogue)
  {
    $variants = explode('_', $this->culture);
    $base = $this->source.DIRECTORY_SEPARATOR.$catalogue.$this->dataSeparator;

    return array(
      $base.$variants[0].$this->dataExt, $base.$this->culture.$this->dataExt,
    );
  }

  public function load($catalogue = 'messages')
  {
    $variants = $this->getCatalogueList($catalogue);

    foreach ($variants as $variant)
    {
      if (isset($this->messages[$variant]))
      {
        return true;
      }

      if (is_file($variant))
      {
        $this->messages[$variant] = include($variant);

        return true;
      }
    }

    return true;
  }

  public function save($catalogue = 'messages')
  {
    return true;
  }

  public function delete($message, $catalogue='messages')
  {
    return true;
  }

  public function update($text, $target, $comments, $catalogue = 'messages')
  {
    return true;
  }
}
