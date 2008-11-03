<?php

class opGenerateModuleTask extends sfBaseTask
{
  protected function configure()
  {
    $this->addArguments(array(
      new sfCommandArgument('opPlugin', sfCommandArgument::REQUIRED, 'The OpenPNE plugin name'),
      new sfCommandArgument('application', sfCommandArgument::REQUIRED, 'The opPlugin application name'),
      new sfCommandArgument('module', sfCommandArgument::REQUIRED, 'The opPlugin module name'),
    ));

    $this->namespace = 'opGenerate';
    $this->name = 'module';
    $this->briefDescription = 'Generates a new module for opPlugin';
    $this->detailedDescription = <<<EOF
The [opGenerate:module|INFO] task creates the basic directory structure
for a new module in an existing opPlugin application:

  [./symfony opGenerate:module opMessagePlugin pc_frontend friend|INFO]

The task can also change the author name found in the [actions.class.php|COMMENT]
if you have configure it in [config/properties.ini|COMMENT]:

  [symfony]
    name=blog
    author=Fabien Potencier <fabien.potencier@sensio.com>

You can customize the default skeleton used by the task by creating a
[/lib/task/skeleton/module|COMMENT] directory.

If a module with the same name already exists in the application,
it throws a [sfCommandException|COMMENT].
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    $plugin = $arguments['opPlugin'];
    $app    = $arguments['application'];
    $module = $arguments['module'];

    // Validate the module name
    if (!preg_match('/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/', $module))
    {
      throw new sfCommandException(sprintf('The module name "%s" is invalid.', $module));
    }

    $moduleDir = sfConfig::get('sf_plugins_dir').'/'.$plugin.'/apps/'.$app.'/modules/'.$module;

    if (is_dir($moduleDir))
    {
      throw new sfCommandException(sprintf('The module "%s" already exists in the "%s" application.in the "%s" opPlugin', 
                                                                    $moduleDir, $app, $plugin));
    }

    $properties = parse_ini_file(sfConfig::get('sf_config_dir').'/properties.ini', true);

    $constants = array(
      'PROJECT_NAME' => isset($properties['symfony']['name']) ? $properties['symfony']['name'] : 'symfony',
      'APP_NAME'     => $app,
      'MODULE_NAME'  => $module,
      'AUTHOR_NAME'  => isset($properties['symfony']['author']) ? $properties['symfony']['author'] : 'Your name here',
    );

    $skeletonDir = dirname(__FILE__).'/skeleton/module';

    // create basic application structure
    $finder = sfFinder::type('any')->discard('.sf');
    $this->getFilesystem()->mirror($skeletonDir.'/module', $moduleDir, $finder);

    // customize php and yml files
    $finder = sfFinder::type('file')->name('*.php', '*.yml');
    $this->getFilesystem()->replaceTokens($finder->in($moduleDir), '##', '##', $constants);
  }
}