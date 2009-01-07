<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opEmojiFilter converts Emoji symbols in the response text
 *
 * Emoji is the picture characters or emoticons used in Japan.
 *
 * @package    OpenPNE
 * @subpackage filter
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opEmojiFilter extends sfFilter
{
 /**
  * Executes this filter.
  *
  * @param sfFilterChain $filterChain A sfFilterChain instance
  */
  public function execute($filterChain)
  {
    $filterChain->execute();

    $response = $this->getContext()->getResponse();
    $request = $this->getContext()->getRequest();
    $content = $response->getContent();

    if (!$request->isMobile())
    {
      list($list, $content) = opToolkit::replacePatternsToMarker($content);
    }

    $content = OpenPNE_KtaiEmoji::convertEmoji($content);

    if (!$request->isMobile())
    {
      $content = str_replace(array_keys($list), array_values($list), $content);
    }

    $response->setContent($content);
  }
}
