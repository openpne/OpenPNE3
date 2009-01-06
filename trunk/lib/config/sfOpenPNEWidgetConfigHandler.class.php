<?php

/**
 * sfOpenPNEWidgetConfigHandler
 *
 * @package    OpenPNE
 * @subpackage config
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class sfOpenPNEWidgetConfigHandler extends sfYamlConfigHandler
{
  public function execute($configFiles)
  {
    $config = $this->parseYamls($configFiles);

    $format = "<?php\n"
            . "sfConfig::add(array('%s' => %s));";
    $result = sprintf($format, 'op_widget_list', var_export($config, true));
    return $result;
  }
}
