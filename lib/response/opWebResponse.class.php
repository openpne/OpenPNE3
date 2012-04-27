<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 *
 * @package    OpenPNE
 * @subpackage response
 * @author     Kousuke Ebihara <ebihara@php.net>
 */
class opWebResponse extends sfWebResponse
{
  protected
    $smtStylesheets = array(),
    $smtJavascripts = array();

  /**
   * Initializes this opWebResponse.
   *
   *  * charset:           The charset to use (utf-8 by default)
   *  * content_type:      The content type (text/html by default)
   *  * send_http_headers: Whether to send HTTP headers or not (true by default)
   *  * http_protocol:     The HTTP protocol to use for the response (HTTP/1.0 by default)
   *
   * @param  sfEventDispatcher $dispatcher  An sfEventDispatcher instance
   * @param  array             $options     An array of options
   *
   * @return bool true, if initialization completes successfully, otherwise false
   *
   * @throws <b>sfInitializationException</b> If an error occurs while initializing this sfResponse
   *
   * @see sfWebResponse->initialize()
   */
  public function initialize(sfEventDispatcher $dispatcher, $options = array())
  {
    parent::initialize($dispatcher, $options);

    $this->smtJavascripts = array_combine($this->positions, array_fill(0, count($this->positions), array()));
    $this->smtStylesheets = array_combine($this->positions, array_fill(0, count($this->positions), array()));
  }

  /**
   * Copies all properties from a given opWebResponse object to the current one.
   *
   * @param sfWebResponse $response  An sfWebResponse instance
   *
   * @see sfWebResponse->copyProperties()
   */
  public function copyProperties(sfWebResponse $response)
  {
    parent::copyProperties($response);

    if ($response instanceof opWebResponse)
    {
      $this->smtStylesheets = $response->getSmtStylesheets(self::RAW);
      $this->smtJavascripts = $response->getSmtJavascripts(self::RAW);
    }
  }

  /**
   * Merges all properties from a given opWebResponse object to the current one.
   *
   * @param sfWebResponse $response  An sfWebResponse instance
   *
   * @see sfWebResponse->merge()
   */
  public function merge(sfWebResponse $response)
  {
    parent::merge($response);

    if ($response instanceof opWebResponse)
    {
      foreach ($this->getPositions() as $position)
      {
        $this->smtJavascripts[$position] = array_merge($this->getSmtJavascripts($position), $response->getSmtJavascripts($position));
        $this->smtStylesheets[$position] = array_merge($this->getSmtStylesheets($position), $response->getSmtStylesheets($position));
      }
    }
  }

  /**
   * @see sfWebResponse
   */
  public function serialize()
  {
    return serialize(array($this->content, $this->statusCode, $this->statusText, $this->options, $this->headerOnly, $this->headers, $this->metas, $this->httpMetas, $this->stylesheets, $this->javascripts, $this->slots, $this->smtStylesheets, $this->smtJavascript));
  }

  /**
   * @see sfWebResponse
   */
  public function unserialize($serialized)
  {
    list($this->content, $this->statusCode, $this->statusText, $this->options, $this->headerOnly, $this->headers, $this->metas, $this->httpMetas, $this->stylesheets, $this->javascripts, $this->slots, $this->smtStylesheets, $this->smtJavascript) = unserialize($serialized);
  }

  public function getTitle()
  {
    $result = parent::getTitle();
    if (!$result)
    {
      $result = opConfig::get('sns_title') ? opConfig::get('sns_title') : opConfig::get('sns_name');
    }

    return $result;
  }

  public function generateMobileUidCookie()
  {
    $request = sfContext::getInstance()->getRequest();
    if (!$request->isMobile() || !$request->isCookie())
    {
      return false;
    }

    $value = opToolkit::getRandom();
    $this->setCookie(opWebRequest::MOBILE_UID_COOKIE_NAME, $value, strtotime('+20years'));

    return $value;
  }

  public function deleteMobileUidCookie()
  {
    $request = sfContext::getInstance()->getRequest();
    if (!$request->isMobile() || !$request->isCookie())
    {
      return false;
    }

    $this->setCookie(opWebRequest::MOBILE_UID_COOKIE_NAME, '', time() - 3600);
  }

  /**
   * Adds a stylesheet to the current smartphone web response.
   *
   * @param string $file      The stylesheet file
   * @param string $position  Position
   * @param string $options   Stylesheet options
   */
  public function addSmtStylesheet($file, $position = '', $options = array())
  {
    $this->validatePosition($position);

    $this->smtStylesheets[$position][$file] = $options;
  }

  /**
   * Removes a stylesheet from the current smartphone web response.
   *
   * @param string $file The stylesheet file to remove
   */
  public function removeSmtStylesheet($file)
  {
    foreach ($this->getPositions() as $position)
    {
      unset($this->smtStylesheets[$position][$file]);
    }
  }

  /**
   * Adds javascript code to the current smartphone web response.
   *
   * @param string $file      The JavaScript file
   * @param string $position  Position
   * @param string $options   Javascript options
   */
  public function addSmtJavascript($file, $position = '', $options = array())
  {
    $this->validatePosition($position);

    $this->smtJavascripts[$position][$file] = $options;
  }

  /**
   * Removes a JavaScript file from the current web response.
   *
   * @param string $file The Javascript file to remove
   */
  public function removeSmtJavascript($file)
  {
    foreach ($this->getPositions() as $position)
    {
      unset($this->smtJavascripts[$position][$file]);
    }
  }

  /**
   * Retrieves stylesheets for the current smartphone web response.
   *
   * By default, the position is sfWebResponse::ALL,
   * and the method returns all stylesheets ordered by position.
   *
   * @param  string  $position The position
   *
   * @return array   An associative array of stylesheet files as keys and options as values
   */
  public function getSmtStylesheets($position = self::ALL)
  {
    if (self::ALL === $position)
    {
      $stylesheets = array();
      foreach ($this->getPositions() as $position)
      {
        foreach ($this->smtStylesheets[$position] as $file => $options)
        {
          $stylesheets[$file] = $options;
        }
      }

      return $stylesheets;
    }
    else if (self::RAW === $position)
    {
      return $this->smtStylesheets;
    }

    $this->validatePosition($position);

    return $this->smtStylesheets[$position];
  }

  /**
   * Retrieves javascript files from the current smartphone web response.
   *
   * By default, the position is sfWebResponse::ALL,
   * and the method returns all javascripts ordered by position.
   *
   * @param  string $position  The position
   *
   * @return array An associative array of javascript files as keys and options as values
   */
  public function getSmtJavascripts($position = self::ALL)
  {
    if (self::ALL === $position)
    {
      $javascripts = array();
      foreach ($this->getPositions() as $position)
      {
        foreach ($this->smtJavascripts[$position] as $file => $options)
        {
          $javascripts[$file] = $options;
        }
      }

      return $javascripts;
    }
    else if (self::RAW === $position)
    {
      return $this->smtJavascripts;
    }

    $this->validatePosition($position);

    return $this->smtJavascripts[$position];
  }

}
