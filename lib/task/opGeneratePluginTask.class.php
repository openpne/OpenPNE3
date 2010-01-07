<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class opGeneratePluginTask extends sfBaseTask
{
  protected function configure()
  {
    $this->addArguments(array(
      new sfCommandArgument('plugin', sfCommandArgument::REQUIRED, 'The OpenPNE plugin name'),
    ));
      
    $this->namespace        = 'opGenerate';
    $this->name             = 'plugin';
    $this->briefDescription = 'Generates a new OpenPNE plugin';
    $this->detailedDescription = <<<EOF
The [opGenerate:plugin|INFO] task creates the basic directory structure
for a new plugin in the OpenPNE project:

  [./symfony opGenerate:plugin opSamplePlugin|INFO]

If a plugin with the same name already exists,
it throws a [sfCommandException|COMMENT].
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    $plugin = $arguments['plugin'];

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

    $fixPerms = new openpnePermissionTask($this->dispatcher, $this->formatter);
    $fixPerms->setCommandApplication($this->commandApplication);
    @$fixPerms->run();
  }
}
