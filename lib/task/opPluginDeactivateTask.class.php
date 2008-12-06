<?php

class opPluginDeactivateTask extends sfBaseTask
{
  protected function configure()
  {
    $this->addArguments(array(
      new sfCommandArgument('name', sfCommandArgument::REQUIRED, 'The plugin name'),
    ));

    $this->namespace        = 'opPlugin';
    $this->name             = 'deactivate';
    $this->briefDescription = 'Deactivates the installed plugin.';
    $this->detailedDescription = <<<EOF
The [opPlugin:deactivate|INFO] task deactivates the installed plugin.
Call it with:

  [./symfony opPlugin:deactivate opSamplePlugin|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    $configuration = $this->createConfiguration('pc_frontend', 'cli');
    $name = $arguments['name'];

    if (!$configuration->isPluginExists($name))
    {
      throw new sfException(sprintf('Plugin "%s" does not installed', $name));
    }

    if ($configuration->isDisabledPlugin($name))
    {
      throw new sfException(sprintf('Plugin "%s" is already disactivated', $name));
    }

    opPlugin::getInstance($name)->setIsActive(false);

    $cc = new sfCacheClearTask($this->dispatcher, $this->formatter);
    $cc->run();
  }
}
