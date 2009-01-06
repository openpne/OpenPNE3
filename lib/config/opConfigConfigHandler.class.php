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
    $categoryAttributeList = "array(\n";

    foreach ($config as $category => $keys)
    {
      $categoryList .= sprintf("'%s' => array(\n", $category);
      $categoryAttributeList .= '';

      if ($keys)
      {
        foreach ($keys as $key => $value)
        {
          if ($key === '_attributes')
          {
            $categoryAttributeList .= sprintf("'%s' => %s,\n", $category, var_export($value, true));
          }
          else
          {
            $categoryList .= sprintf("'%s',\n", $key);
            $data .= sprintf("'%s' => %s,\n", $key, var_export($value, true));
          }
        }
      }

      $categoryList .= "),\n";
    }

    $data .= "),\n";
    $categoryList .= "),\n";
    $categoryAttributeList .= "),\n";

    $format = "<?php\n"
            . "sfConfig::add(array('%s' => %s));\n"
            . "sfConfig::add(array('%s' => %s));\n"
            . "sfConfig::add(array('%s' => %s));";

    return sprintf($format, $prefix.'config', $data, $prefix.'category', $categoryList, $prefix.'category_attribute', $categoryAttributeList);
  }
}
