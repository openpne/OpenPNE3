<?php
class opZendLoader
{
  /**
   * Autoload classes.
   * This method replaces Zend_Loader::registerAutoload() because it's been deprecated from zf 1.8.0
   * and will be removed from 2.0.0.
   *
   * @param string $class
   * @param mixed $enabled
   * @return
   */
  public static function registerAutoload($class = 'Zend_Loader', $enabled = true)
  {
    require_once 'Zend/Loader/Autoloader.php';
    $autoloader = Zend_Loader_Autoloader::getInstance();
    $autoloader->setFallbackAutoloader(true);

    if ('Zend_Loader' != $class)
    {
      Zend_Loader::loadClass($class);
      $methods = get_class_methods($class);
      if (!in_array('autoload', (array) $methods))
      {
        require_once 'Zend/Exception.php';
        throw new Zend_Exception("The class \"$class\" does not have an autoload() method");
      }

      $callback = array($class, 'autoload');

      if ($enabled)
      {
        $autoloader->pushAutoloader($callback);
      }
      else
      {
        $autoloader->removeAutoloader($callback);
      }
    }
    else
    {
      if (!$enabled)
      {
        Zend_Loader_Autoloader::resetInstance();
        spl_autoload_unregister(array('Zend_Loader_Autoloader', 'autoload'));
      }
    }
  }
}