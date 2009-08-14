<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opMailRoute
 *
 * @package    OpenPNE
 * @subpackage routing
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opMailRoute extends sfRoute
{
  protected
    $member = null,
    $nonAuth = false;

  public function __construct($pattern, array $defaults = array(), array $requirements = array(), array $options = array())
  {
    parent::__construct($pattern, $defaults, $requirements, $options);

    if (isset($this->options['non_auth']))
    {
      $this->nonAuth = $this->options['non_auth'];
    }
  }

  public function getMember()
  {
    if (is_null($this->member))
    {
      $config = Doctrine::getTable('MemberConfig')->retrieveByNameAndValue('mobile_address', $this->context['from_address']);
      if ($config)
      {
        $this->member = $config->getMember();
        if (isset($this->requirements['hash']) && !$this->nonAuth)
        {
          $hash = $this->member->getMailAddressHash();
          if (!isset($this->parameters['hash']) || $hash !== $this->parameters['hash'])
          {
            $this->member = null;
          }
        }
      }
    }

    return $this->member;
  }

  protected function fixSuffix()
  {
    parent::fixSuffix();

    if (sfConfig::get('op_is_mail_address_contain_hash', false) && !$this->nonAuth)
    {
      $this->pattern = $this->pattern.'.:hash';
    }
  }

  public function generate($params, $context = array(), $absolute = false)
  {
    if (!$this->compiled)
    {
      $this->compile();
    }

    $url = $this->pattern;
    $defaults = $this->mergeArrays($this->getDefaultParameters(), $this->defaults);
    $tparams = $this->mergeArrays($defaults, $params);

    // all params must be given
    if ($diff = array_diff_key($this->variables, $tparams))
    {
      throw new InvalidArgumentException(sprintf('The "%s" route has some missing mandatory parameters (%s).', $this->pattern, implode(', ', $diff)));
    }

    // replace variables
    $variables = $this->variables;
    uasort($variables, create_function('$a, $b', 'return strlen($a) < strlen($b);'));
    foreach ($variables as $variable => $value)
    {
      $url = str_replace($value, urlencode($tparams[$variable]), $url);
    }

    return $url;
  }
}
