<?php

/**
 * sfOpenPNEConfigHandler
 *
 * @package    OpenPNE
 * @subpackage config
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
abstract class sfOpenPNEConfigHandler extends sfYamlConfigHandler
{
  protected $prefix = 'openpne_';

  public function execute($configFiles)
  {
    $config = $this->parseYamls($configFiles);

    $data = "array(\n";
    $category_list = "array(\n";

    foreach ($config as $category => $keys)
    {
      $category_list .= sprintf("'%s' => array(\n", $category);
      foreach ($keys as $key => $value) {
        $value = $this->convertConfig($key, $value);
        $data .= sprintf("'%s' => %s,\n", $key, var_export($value, true));
        $category_list .= sprintf("'%s',\n", $key);
      }
      $category_list .= "),\n";
    }

    $data .= "),\n";
    $category_list .= "),\n";

    return sprintf("<?php sfConfig::add(array('%s' => %s));\nsfConfig::add(array('%s' => %s));", $this->prefix.'config', $data, $this->prefix.'category', $category_list);
  }

  protected function convertConfig($key, $value)
  {
    return $value;
  }
}
