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
    $config = $this->parseYamls($configFiles);
    foreach ($config as $k => $v)
    {
      if (!isset($v['config']))
      {
        $config[$k]['config'] = array();
      }

      $config[$k]['config']['viewable_privilege'] = array(
        'Name'       => 'viewable_privilege',
        'Caption'    => '公開範囲',
        'FormType'   => 'select',
        'ValueType'  => 'int',
        'IsRequired' => true,
        'Default'    => 1,
        'Choices'    => array(
          4 => 'All Users on the Web',
          1 => 'All Members',
        ),
      );

      if (isset($v['viewable_privilege']))
      {
        $config[$k]['config']['viewable_privilege']['Default'] = $v['viewable_privilege'];
      }
    }

    $format = "<?php\n"
            . "return %s;";
    $result = sprintf($format, var_export($config, true));
    return $result;
  }
}
