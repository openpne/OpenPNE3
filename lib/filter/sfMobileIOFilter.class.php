<?php

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
