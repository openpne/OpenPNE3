<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opPluginConfigHandler
 *
 * @package    OpenPNE
 * @subpackage config
 * @author     Kousuke Ebihara <ebihara@php.net>
 */
class opPluginConfigHandler extends sfYamlConfigHandler
{
  public function execute($configFiles)
  {
    $prefix = 'op_plugin_';
    $config = $this->parseYamls($configFiles);

    $data = "array(\n";

    foreach ($config as $key => $value)
    {
      $data .= sprintf("'%s%s' => %s\n", $prefix, $key, var_export($value, true));
    }

    $data .= ")\n";

    $format = "<?php\n"
            . "sfConfig::add(%s);\n";

    return sprintf($format, $data);
  }
}
