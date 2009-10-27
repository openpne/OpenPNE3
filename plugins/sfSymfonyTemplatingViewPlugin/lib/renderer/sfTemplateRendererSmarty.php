<?php

class sfTemplateRendererSmarty extends sfTemplateRenderer
{
  protected $smarty = null;

  public function __construct(Smarty $instance = null)
  {
    if (!$instance)
    {
      $instance = $this->createSmartyInstance();
    }

    $this->smarty = $instance;
  }

  protected function createSmartyInstance()
  {
    require_once 'Smarty.class.php';

    $smartyCacheDir = sfConfig::get('sf_cache_dir');

    $smarty = new Smarty();
    $smarty->compile_dir = $smartyCacheDir.DIRECTORY_SEPARATOR.'smarty_templates_c';
    $smarty->cache_dir = $smartyCacheDir.DIRECTORY_SEPARATOR.'smarty_cache';

    $this->mkdir($smarty->compile_dir);
    $this->mkdir($smarty->cache_dir);

    return $smarty;
  }

  public function evaluate(sfTemplateStorage $template, array $parameters = array())
  {
    $this->smarty->assign($parameters);

    if ($template instanceof sfTemplateStorageFile)
    {
      return $this->smarty->fetch($template);
    }
    elseif ($template instanceof sfTemplateStorageString)
    {
      $filename = tempnam(sfConfig::get('sf_cache_dir'), 'SMARTY');
      file_put_contents($filename, (string)$template);
      $result = $this->smarty->fetch($filename);

      unlink($filename);

      return $result;
    }

    return false;
  }

  protected function mkdir($path, $mode = 0777)
  {
    if (is_dir($path))
    {
      return true;
    }

    return @mkdir($path, $mode, true);
  }
}
