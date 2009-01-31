<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * @package    OpenPNE
 * @subpackage util
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opBrowser extends sfBrowser
{
  /**
   * @see sfBrowserBase::doClick()
   * @see http://trac.symfony-project.org/ticket/5748
   */
  public function doClick($name, $arguments = array(), $options = array())
  {
    $position = isset($options['position']) ? $options['position'] - 1 : 0;

    $dom = $this->getResponseDom();

    if (!$dom)
    {
      throw new LogicException('Cannot click because there is no current page in the browser.');
    }

    $xpath = new DomXpath($dom);

    $method = strtolower(isset($options['method']) ? $options['method'] : 'get');

    // text link
    if ($link = $xpath->query(sprintf('//a[.="%s"]', $name))->item($position))
    {
      if (in_array($method, array('post', 'put', 'delete')))
      {
        if (isset($options['_with_csrf']) && $options['_with_csrf'])
        {
          $arguments['_with_csrf'] = true;
        }

        return array($link->getAttribute('href'), $method, $arguments);
      }
      else
      {
        return array($link->getAttribute('href'), 'get', array());
      }
    }

    // image link
    if ($link = $xpath->query(sprintf('//a/img[@alt="%s"]/ancestor::a', $name))->item($position))
    {
      if (in_array($method, array('post', 'put', 'delete')))
      {
        return array($link->getAttribute('href'), $method, $arguments);
      }
      else
      {
        return array($link->getAttribute('href'), 'get', $arguments);
      }
    }

    // form
    if (!$form = $xpath->query(sprintf('//input[((@type="submit" or @type="button") and @value="%s") or (@type="image" and @alt="%s")]/ancestor::form', $name, $name))->item($position))
    {
      if (!$form = $xpath->query(sprintf('//button[.="%s" or @id="%s" or @name="%s"]/ancestor::form', $name, $name, $name))->item($position))
      {
        throw new InvalidArgumentException(sprintf('Cannot find the "%s" link or button.', $name));
      }
    }

    // form attributes
    $url = $form->getAttribute('action');
    if (!$url || '#' == $url)
    {
      $url = $this->stack[$this->stackPosition]['uri'];
    }
    $method = strtolower(isset($options['method']) ? $options['method'] : ($form->getAttribute('method') ? $form->getAttribute('method') : 'get'));

    // merge form default values and arguments
    $defaults = array();
    $arguments = sfToolkit::arrayDeepMerge($this->fields, $arguments);

    foreach ($xpath->query('descendant::input | descendant::textarea | descendant::select', $form) as $element)
    {
      $elementName = $element->getAttribute('name');
      $nodeName    = $element->nodeName;
      $value       = null;

      if ($nodeName == 'input' && ($element->getAttribute('type') == 'checkbox' || $element->getAttribute('type') == 'radio'))
      {
        if ($element->getAttribute('checked'))
        {
          $value = $element->hasAttribute('value') ? $element->getAttribute('value') : '1';
        }
      }
      else if ($nodeName == 'input' && $element->getAttribute('type') == 'file')
      {
        $file = array_key_exists($elementName, $arguments) ? $arguments[$elementName] : sfToolkit::getArrayValueForPath($arguments, $elementName, '');
        if (is_array($file))
        {
          $filename = isset($file['name']) ? $file['name'] : '';
          $filetype = isset($file['type']) ? $file['type'] : '';
        }
        else
        {
          $filename = $file;
          $filetype = '';
        }

        if (is_readable($filename))
        {
          $fileError = UPLOAD_ERR_OK;
          $fileSize = filesize($filename);
        }
        else
        {
          $fileError = UPLOAD_ERR_NO_FILE;
          $fileSize = 0;
        }

        unset($arguments[$elementName]);

        $this->parseArgumentAsArray($elementName, array('name' => basename($filename), 'type' => $filetype, 'tmp_name' => $filename, 'error' => $fileError, 'size' => $fileSize), $this->files);
      }
      else if (
        $nodeName == 'input'
        &&
        (($element->getAttribute('type') != 'submit' && $element->getAttribute('type') != 'button') || $element->getAttribute('value') == $name)
        &&
        ($element->getAttribute('type') != 'image' || $element->getAttribute('alt') == $name)
      )
      {
        $value = $element->getAttribute('value');
      }
      else if ($nodeName == 'textarea')
      {
        $value = '';
        foreach ($element->childNodes as $el)
        {
          $value .= $dom->saveXML($el);
        }
      }
      else if ($nodeName == 'select')
      {
        if ($multiple = $element->hasAttribute('multiple'))
        {
          $elementName = str_replace('[]', '', $elementName);
          $value = array();
        }
        else
        {
          $value = null;
        }

        $found = false;
        foreach ($xpath->query('descendant::option', $element) as $option)
        {
          if ($option->getAttribute('selected'))
          {
            $found = true;
            if ($multiple)
            {
              $value[] = $option->getAttribute('value');
            }
            else
            {
              $value = $option->getAttribute('value');
            }
          }
        }

        // if no option is selected and if it is a simple select box, take the first option as the value
        $option = $xpath->query('descendant::option', $element)->item(0);
        if (!$found && !$multiple && $option instanceof DOMElement)
        {
          $value = $option->getAttribute('value');
        }
      }

      if (null !== $value)
      {
        $this->parseArgumentAsArray($elementName, $value, $defaults);
      }
    }

    // create request parameters
    $arguments = sfToolkit::arrayDeepMerge($defaults, $arguments);
    if (in_array($method, array('post', 'put', 'delete')))
    {
      return array($url, $method, $arguments);
    }
    else
    {
      $queryString = http_build_query($arguments, null, '&');
      $sep = false === strpos($url, '?') ? '?' : '&';

      return array($url.($queryString ? $sep.$queryString : ''), 'get', array());
    }
  }
}
