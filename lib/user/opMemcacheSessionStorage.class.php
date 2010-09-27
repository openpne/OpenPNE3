<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opMemcacheSessionStorage
 *
 * @package    OpenPNE
 * @subpackage user
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opMemcacheSessionStorage extends sfSessionStorage
{
  protected $memcache = null;

  /**
   * Available options:
   *
   * - host:       The default host (default to localhost)
   * - port:       The port for the default server (default to 11211)
   * - persistent: true if the connection must be persistent, false otherwise (true by default)
   *
   * @param  array $options  An associative array of options
   *
   * @see sfSessionStorage
   * @see sfMemcacheCache
   */
  public function initialize($options = array())
  {
    $isAutoStart = true;
    if (isset($options['auto_start']))
    {
      $isAutoStart = $options['auto_start'];
    }
    // disable auto_start
    $options['auto_start'] = false;

    // initialize the parent
    parent::initialize($options);

    if (!class_exists('Memcache'))
    {
      throw new sfInitializationException('You must have memcache installed and enabled to use sfMemcacheCache class.');
    }

    $this->memcache = new Memcache();

    // use this object as the session handler
    session_set_save_handler(array($this, 'sessionOpen'),
                             array($this, 'sessionClose'),
                             array($this, 'sessionRead'),
                             array($this, 'sessionWrite'),
                             array($this, 'sessionDestroy'),
                             array($this, 'sessionGC'));

    if ($isAutoStart && !parent::$sessionStarted)
    {
      // start our session
      session_start();
      parent::$sessionStarted = true;
    }
  }

  /**
   * Closes a session.
   *
   * @return boolean true, if the session was closed, otherwise false
   */
  public function sessionClose()
  {
    // do nothing
    return true;
  }

  /**
   * Opens a session.
   *
   * @param  string $path  (ignored)
   * @param  string $name  (ignored)
   *
   * @return boolean true, if the session was opened, otherwise an exception is thrown
   */
  public function sessionOpen($path = null, $name = null)
  {
    $method = !empty($this->options['persistent']) ? 'pconnect' : 'connect';
    $host = empty($this->options['host']) ? 'localhost' : $this->options['host'];
    $port = empty($this->options['port']) ? 11211 : (int)$this->options['port'];
    $timeout = empty($this->options['timeout']) ? 11211 : (int)$this->options['timeout'];

    if (!$this->memcache->$method($host, $port, $timeout))
    {
      throw new sfInitializationException(sprintf('Unable to connect to the memcache server (%s:%s).', $host, $port));
    }

    return true;
  }

  /**
   * Destroys a session.
   *
   * @param  string $id  A session ID
   *
   * @return bool true, if the session was destroyed, otherwise an exception is thrown
   */
  public function sessionDestroy($id)
  {
    return $this->memcache->delete($id);
  }

  /**
   * Cleans up old sessions.
   *
   * @param  int $lifetime  The lifetime of a session
   *
   * @return bool true, if old sessions have been cleaned, otherwise an exception is thrown
   */
  public function sessionGC($lifetime)
  {
    // do nothing
    return true;
  }

  /**
   * Reads a session.
   *
   * @param  string $id  A session ID
   *
   * @return string The session data if the session was read or created
   */
  public function sessionRead($id)
  {
    return (string)$this->memcache->get($id);
  }

  /**
   * Writes session data.
   *
   * @param  string $id    A session ID
   * @param  string $data  A serialized chunk of session data
   *
   * @return bool true, if the session was written, otherwise an exception is thrown
   */
  public function sessionWrite($id, $data)
  {
    if (!$id || !$data)
    {
      return false;
    }

    $lifetime = ini_get("session.gc_maxlifetime");

    return $this->memcache->set($id, $data, 0, $lifetime);
  }

  /**
   * Regenerates id that represents this storage.
   *
   * @param  boolean $destroy Destroy session when regenerating?
   */
  public function regenerate($destroy = false)
  {
    if (self::$sessionIdRegenerated)
    {
      return;
    }

    $currentId = session_id();

    parent::regenerate($destroy);

    $newId = session_id();
    $this->sessionRead($newId);

    return $this->sessionWrite($newId, $this->sessionRead($currentId));
  }

  /**
   * Executes the shutdown procedure.
   */
  public function shutdown()
  {
    parent::shutdown();
  }
}
