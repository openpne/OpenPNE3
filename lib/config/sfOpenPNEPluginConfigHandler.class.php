<?php

/**
 * Copyright (C) 2005-2009 OpenPNE Project
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *  http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

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
