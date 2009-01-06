<?php

/**
 * Copyright (C) 2005-2009 OpenPNE Project
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *  http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
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
