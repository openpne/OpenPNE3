<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opGadgetConfigConfigHandler
 *
 * @package    OpenPNE
 * @subpackage config
 * @author     Shogo Kawahara <kawahara@tejimaya.net>
 */
class opGadgetConfigConfigHandler extends sfYamlConfigHandler
{
  public function execute($configFiles)
  {
    $prefix = strtolower($this->getParameterHolder()->get('prefix', ''));

    $config = $this->parseYamls($configFiles);

    $format = "<?php\n"
            . "sfConfig::add(array('%s' => %s));";
    $result = sprintf($format, 'op_gadget_'.$prefix.'config', var_export($config, true));
    return $result;
  }
}
