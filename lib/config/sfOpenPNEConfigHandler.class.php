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
    $categoryList = "array(\n";

    foreach ($config as $category => $keys)
    {
      $categoryList .= sprintf("'%s' => array(\n", $category);
      if ($keys)
      {
        foreach ($keys as $key => $value)
        {
          $data .= sprintf("'%s' => %s,\n", $key, var_export($value, true));
          $categoryList .= sprintf("'%s',\n", $key);
        }
      }
      $categoryList .= "),\n";
    }

    $data .= "),\n";
    $categoryList .= "),\n";

    $format = "<?php\n"
            . "sfConfig::add(array('%s' => %s));\n"
            . "sfConfig::add(array('%s' => %s));";

    return sprintf($format, $this->prefix.'config', $data, $this->prefix.'category', $categoryList);
  }
}
