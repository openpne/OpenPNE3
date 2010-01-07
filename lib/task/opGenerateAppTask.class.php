<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class opGenerateAppTask extends sfBaseTask
{
  protected function configure()
  {
    $this->addArguments(array(
      new sfCommandArgument('plugin', sfCommandArgument::REQUIRED, 'The OpenPNE plugin name'),
      new sfCommandArgument('application', sfCommandArgument::REQUIRED, 'The application name'),
    ));

    $this->namespace = 'opGenerate';
    $this->name = 'app';
    $this->briefDescription = 'Generates a new application for OpenPNE plugin';
    $this->detailedDescription = <<<EOF
The [opGenerate:app|INFO] task creates the basic directory structure
for a new application in an existing OpenPNE plugin:

  [./symfony opGenerate:app opSamplePlugin pc_frontend|INFO]

If an application with the same name already exists in the plugin,
it throws a [sfCommandException|COMMENT].
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    $plugin = $arguments['plugin'];
    $app = $arguments['application'];

    // Validate the application name
    if (!preg_match('/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/', $app))
    {
      throw new sfCommandException(sprintf('The application name "%s" is invalid.', $app));
    }

    $appDir = sfConfig::get('sf_plugins_dir').'/'.$plugin.'/apps/'.$app;

    if (is_dir($appDir))
    {
      throw new sfCommandException(sprintf('The application "%s" already exists in the "%s" plugin.', $appDir, $plugin));
    }

    // Create basic application structure
    $finder = sfFinder::type('any')->discard('.sf');
    $this->getFilesystem()->mirror(dirname(__FILE__).'/skeleton/app/app', $appDir, $finder);

    $fixPerms = new openpnePermissionTask($this->dispatcher, $this->formatter);
    $fixPerms->setCommandApplication($this->commandApplication);
    $fixPerms->run();
  }
}
