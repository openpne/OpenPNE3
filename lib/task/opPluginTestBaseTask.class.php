<?php

/**
 * util for opPlugin:test-unit and opPlugin:test-functional.
 * 
 * @package    OpenPNE
 * @subpackage task
 * @auther     Hiromi Hishida <info@77-web.com>
 */
abstract class opPluginTestBaseTask extends sfBaseTask
{
  protected function launchTestsInDir($baseDir)
  {
    if (!is_dir($baseDir))
    {
      throw new RuntimeException(sprintf('No test exists in the specified path: %s', $baseDir));
    }

    $h = new sfLimeHarness();
    $h->base_dir = $baseDir;
    
    $files = sfFinder::type('file')->follow_link()->name('*Test.php')->in($h->base_dir);
    if (0 < count($files))
    {
      $h->register($files);

      return $h->run() ? 0 : 1;
    }

    return 0;
  }
}