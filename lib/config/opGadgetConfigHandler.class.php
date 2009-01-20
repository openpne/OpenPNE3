<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opGadgetConfigHandler
 *
 * @package    OpenPNE
 * @subpackage config
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opGadgetConfigHandler extends sfYamlConfigHandler
{
  public function execute($configFiles)
  {
    // get our prefix
    $prefix = strtolower($this->getParameterHolder()->get('prefix', ''));

    $config = $this->parseYamls($configFiles);

    $format = "<?php\n"
            . "sfConfig::add(array('%s' => %s));";
    $result = sprintf($format, 'op_'.$prefix.'gadget_list', var_export($config, true));
    return $result;
  }
}
