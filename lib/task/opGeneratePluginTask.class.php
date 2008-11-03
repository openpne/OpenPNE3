<?php

class opGeneratePluginTask extends sfBaseTask
{
  protected function configure()
  {
    $this->addArguments(array(
      new sfCommandArgument('opPlugin', sfCommandArgument::REQUIRED, 'The OpenPNE plugin name'),
    ));
      
    $this->namespace        = 'opGenerate';
    $this->name             = 'plugin';
    $this->briefDescription = 'Generates a new OpenPNE Plugin';
    $this->detailedDescription = <<<EOF
The [opGenerate:plugin|INFO] task creates the basic directory structure
for a new plugin in the OpenPNE project:

  [./symfony opGenerate:plugin opMessagePlugin |INFO]

If an plugin with the same name already exists,
it throws a [sfCommandException|COMMENT].
  
EOF;
    // add arguments here, like the following:
    //$this->addArgument('application', sfCommandArgument::REQUIRED, 'The application name');
    // add options here, like the following:
    //$this->addOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev');
  }

  protected function execute($arguments = array(), $options = array())
  {
    $plugin = $arguments['opPlugin'];

    // Validate the application name
    if (!preg_match('/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/', $plugin))
    {
      throw new sfCommandException(sprintf('The OpenPNE plugin name "%s" is invalid.', $plugin));
    }

    $opPluginDir = sfConfig::get('sf_plugins_dir').'/'.$plugin;

    if (is_dir($opPluginDir))
    {
      throw new sfCommandException(sprintf('The OpenPNE plugin "%s" already exists.', $opPluginDir));
    }
      
    // create basic opPlugin structure
    $finder = sfFinder::type('any')->discard('.sf');
    $this->getFilesystem()->mirror(dirname(__FILE__).'/skeleton/opPlugin', $opPluginDir, $finder);

    $fixPerms = new sfProjectPermissionsTask($this->dispatcher, $this->formatter);
    $fixPerms->setCommandApplication($this->commandApplication);
    $fixPerms->run();
  }
}