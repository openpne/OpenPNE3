<?php

class opUpdate_3_0_1_dev_200902052050_to_3_0_1_dev_200902061952
{
  public function update()
  {
    $configPath = sfConfig::get('sf_config_dir').'/ProjectConfiguration.class.php';
    if (!is_writable($configPath))
    {
      return false;
    }

    $pattern = '/(\$this\->(enableAllPluginsExcept|disablePlugins)\(\s*array\(([^\)]*)(([\'"])sfDoctrinePlugin\5,?\s*))/m';
    $replace = '$this->\2(array(\3';
    $content = file_get_contents($configPath);
    $content = preg_replace($pattern, $replace, $content);
    file_put_contents($configPath, $content);
  }

  public function doUpdate()
  {
    $this->update();
  }
}
