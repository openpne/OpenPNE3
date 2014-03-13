<?php
/**
 * Licensed to the Apache Software Foundation (ASF) under one
 * or more contributor license agreements.  See the NOTICE file
 * distributed with this work for additional information
 * regarding copyright ownership.  The ASF licenses this file
 * to you under the Apache License, Version 2.0 (the
 * "License"); you may not use this file except in compliance
 * with the License.  You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing,
 * software distributed under the License is distributed on an
 * "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
 * KIND, either express or implied.  See the License for the
 * specific language governing permissions and limitations
 * under the License.
 */

// according to features/core/io.js, this is high on the list of things to scrap
define('UNPARSEABLE_CRUFT', "throw 1; < don't be evil' >");

/**
 * Handles the gadget.io.makeRequest requests
 */
class MakeRequestHandler extends ProxyBase {
  /**
   * @var SingingFetcherFactory
   */
  private $signingFetcherFactory;
  
  public function __construct($context, $signingFetcherFactory) {
    $this->context = $context;
    $this->signingFetcherFactory = $signingFetcherFactory;
  }

  /**
   * Fetches content and returns it in JSON format
   *
   * @param string $url the url to fetch
   * @param GadgetSigner $signer the request signer to use
   * @param string $method the http method to use (get or post) in making the request
   */
  public function fetchJson($url, $signer, $method) {
    $url = $this->validateUrl($url);
    // Fetch the content and convert it into JSON.
    // TODO: Fetcher needs to handle variety of HTTP methods.
    $result = $this->fetchContentDivert($url, $method, $signer);
    if (! isset($result)) {
      //OAuthFetcher only
      $metadata = $this->oauthFetcher->getResponseMetadata();
      $json = array($url => $metadata);
      $json = json_encode($json);
      $output = UNPARSEABLE_CRUFT . $json;
      $this->setCachingHeaders();
      header("Content-Type: application/json; charset=utf-8", true);
      echo $output;
      die();
    }
    $status = (int)$result->getHttpCode();
    header("Content-Type: application/json; charset=utf-8", true);
    $output = '';
    if (isset($_REQUEST['contentType']) && $_REQUEST['contentType'] == 'FEED' && $status == 200) {
      $this->parseFeed($result, $url);
    } else {
      $body = $result->getResponseContent();
    }
    $responseArray = array('body' => $body, 'rc' => $status);
    foreach ($result->getMetadatas() as $key => $value) {
      $responseArray[$key] = $value;
    }
    $json = array($url => $responseArray);
    $json = json_encode($json);
    $output = UNPARSEABLE_CRUFT . $json;
    if ($status == 200) {
      // only set caching headers if the result was 'OK'
      $this->setCachingHeaders();
    }
    echo $output;
  }

  /**
   * Fetches content using either OAUTH, SIGNED or NONE type signing
   *
   * @param string $url
   * @param string $method
   * @param SecurityTokenDecoder $signer
   * @return RemoteContentRequest
   */
  private function fetchContentDivert($url, $method, $signer) {
    $basicFetcher = new BasicRemoteContentFetcher();
    $basicRemoteContent = new BasicRemoteContent($basicFetcher, $this->signingFetcherFactory, $signer);
    $request = $this->buildRequest($url, $method, $signer);
    $request->getOptions()->ignoreCache = $this->context->getIgnoreCache();
    return $basicRemoteContent->fetch($request);
  }

  /**
   * Handles (RSS & Atom) Type.FEED parsing using Zend's feed parser
   *
   * @return response string, either a json encoded feed structure or an error message
   */
  private function parseFeed($result, $url) {
    // require 'external/Zend/Feed.php';
    $numEntries = $_REQUEST['numEntries'];
    $getSummaries = ! empty($_REQUEST['getSummaries']) && $_REQUEST['getSummaries'] != 'false' ? true : false;
    $channel = array();
    if ((int)$result->getHttpCode() == 200) {
      $content = $result->getResponseContent();
      try {
        $feed = Zend_Feed::importString($content);
        if ($feed instanceof Zend_Feed_Rss) {
          // Try get author
          if ($feed->author()) {
            $author = $feed->author();
          } else {
            if ($feed->creator()) {
              $author = $feed->creator();
            } else {
              $author = null;
            }
          }
          // Loop over each channel item and store relevant data
          $counter = 0;
          $channel['Entry'] = array();
          foreach ($feed as $item) {
            if ($counter >= $numEntries) {
              break;
            }
            $_entry = array();
            $_entry['Title'] = $item->title();
            $_entry['Link'] = $item->link();
            if ($getSummaries && $item->description()) {
              $_entry['Summary'] = $item->description();
            }
            $date = 0;
            if ($item->date()) {
              $date = strtotime($item->date());
            } else {
              if ($item->pubDate()) {
                $date = strtotime($item->pubDate());
              }
            }
            $_entry['Date'] = $date;
            $channel['Entry'][] = $_entry;
            // Remember author if first found
            if (empty($author) && $item->author()) {
              $author = $item->author();
            } else if ($item->creator()) {
              $author = $item->creator();
            }
            $counter ++;
          }
          $channel['Title'] = $feed->title();
          $channel['URL'] = $url;
          $channel['Description'] = $feed->description();
          if ($feed->link()) {
            if (is_array($feed->link())) {
              foreach ($feed->link() as $_link) {
                if ($_link->nodeValue) $channel['Link'] = $_link->nodeValue;
              }
            } else {
              $channel['Link'] = $feed->link();
            }
          }
          if ($author != null) {
            $channel['Author'] = $author;
          }
        } elseif ($feed instanceof Zend_Feed_Atom) {
          // Try get author
          if ($feed->author()) {
            if ($feed->author->name()) {
              $author = $feed->author->name();
            } else if ($feed->author->email()) {
              $author = $feed->author->email();
            } else {
              $author = $feed->author();
            }
          } else {
            $author = null;
          }
          // Loop over each entries and store relevant data
          $counter = 0;
          $channel['Entry'] = array();
          foreach ($feed as $entry) {
            if ($counter >= $numEntries) {
              break;
            }
            $_entry = array();
            $_entry['Title'] = $entry->title();
            // get Link if rel="alternate"
            if ($entry->link('alternate')) {
              $_entry['Link'] = $entry->link('alternate');
            } else {
              // if there's no alternate, pick the one without "rel" attribtue
              $_links = $entry->link;
              if (is_array($_links)) {
                foreach ($_links as $_link) {
                  if (empty($_link['rel'])) {
                    $_entry['Link'] = $_link['href'];
                    break;
                  }
                }
              } else {
                $_entry['Link'] = $_links['href'];
              }
            }
            if ($getSummaries && $entry->summary()) {
              $_entry['Summary'] = $entry->summary();
            }
            $date = 0;
            if ($entry->updated()) {
              $date = strtotime($entry->updated());
            } else {
              if ($entry->published()) {
                $date = strtotime($entry->published());
              }
            }
            $_entry['Date'] = $date;
            $channel['Entry'][] = $_entry;
            // Remember author if first found
            if (empty($author) && $entry->author()) {
              if ($entry->author->name()) {
                $author = $entry->author->name();
              } else if ($entry->author->email()) {
                $author = $entry->author->email();
              } else {
                $author = $entry->author();
              }
            } elseif (empty($author)) {
              $author = null;
            }
            $counter ++;
          }
          $channel['Title'] = $feed->title();
          $channel['URL'] = $url;
          $channel['Description'] = $feed->subtitle();
          // get Link if rel="alternate"
          if ($feed->link('alternate')) {
            $channel['Link'] = $feed->link('alternate');
          } else {
            // if there's no alternate, pick the one without "rel" attribtue
            $_links = $feed->link;
            if (is_array($_links)) {
              foreach ($_links as $_link) {
                if (empty($_link['rel'])) {
                  $channel['Link'] = $_link['href'];
                  break;
                }
              }
            } else {
              $channel['Link'] = $_links['href'];
            }
          }
          if (! empty($author)) {
            $channel['Author'] = $author;
          }
        } else {
          throw new Exception('Invalid feed type');
        }
        $resp = json_encode($channel);
      } catch (Zend_Feed_Exception $e) {
        $resp = 'Error parsing feed: ' . $e->getMessage();
      }
    } else {
      // feed import failed
      $resp = "Error fetching feed, response code: " . $result->getHttpCode();
    }
    return $resp;
  }
}
