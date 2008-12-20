<?php

/**
 * opConfigConfigHandler
 *
 * @package    OpenPNE
 * @subpackage config
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opConfigConfigHandler extends sfYamlConfigHandler
{
  public function execute($configFiles)
  {
    // get our prefix
    $prefix = strtolower($this->getParameterHolder()->get('prefix', 'openpne_'));

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

    return sprintf($format, $prefix.'config', $data, $prefix.'category', $categoryList);
  }
}
