<?php

/**
 * sfOpenPNEPluginConfigHandler
 *
 * @package    OpenPNE
 * @subpackage config
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class sfOpenPNEPluginConfigHandler extends sfYamlConfigHandler
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
