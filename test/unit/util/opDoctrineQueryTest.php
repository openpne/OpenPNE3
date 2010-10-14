<?php

include_once dirname(__FILE__).'/../../bootstrap/unit.php';

class myQuery extends opDoctrineQuery
{
  public static $lastQueryCacheHash = '';

  public function calculateQueryCacheHash()
  {
    self::$lastQueryCacheHash = parent::calculateQueryCacheHash();

    return self::$lastQueryCacheHash;
  }
}

$_app = 'pc_frontend';
$_env = 'test';

$configuration = ProjectConfiguration::getApplicationConfiguration($_app, $_env, true);
new sfDatabaseManager($configuration);

Doctrine_Manager::getInstance()->setAttribute(Doctrine::ATTR_QUERY_CLASS, 'myQuery');

$t = new lime_test(null, new lime_output_color());

// --

$keys = array();

Doctrine::getTable('AdminUser')->find(1);
$keys['find'] = myQuery::$lastQueryCacheHash;

Doctrine::getTable('AdminUser')->findAll();
$keys['find_all'] = myQuery::$lastQueryCacheHash;

Doctrine::getTable('AdminUser')->findById(1);
$keys['find_by_id'] = myQuery::$lastQueryCacheHash;

Doctrine::getTable('AdminUser')->findOneById(1);
$keys['find_one_by_id'] = myQuery::$lastQueryCacheHash;

Doctrine::getTable('AdminUser')->findByIdAndUsername(1, 'admin');
$keys['find_by_id_and_username'] = myQuery::$lastQueryCacheHash;

Doctrine::getTable('AdminUser')->findOneByIdAndUsername(1, 'admin');
$keys['find_one_by_id_and_username'] = myQuery::$lastQueryCacheHash;

Doctrine::getTable('AdminUser')->findByUsernameAndPassword('admin', 'password');
$keys['find_by_username_and_password'] = myQuery::$lastQueryCacheHash;

Doctrine::getTable('AdminUser')->findOneByUsernameAndPassword('admin', 'password');
$keys['find_one_by_username_and_password'] = myQuery::$lastQueryCacheHash;

$t->isnt($keys['find'], $keys['find_all'], '->find() and ->findAll() generates different query cache keys');
$t->isnt($keys['find'], $keys['find_by_id'], '->find() and ->findById() generates different query cache keys');
$t->is($keys['find'], $keys['find_one_by_id'], '->find() and ->findOneById() generates same query cache keys');
$t->isnt($keys['find'], $keys['find_by_id_and_username'], '->find() and ->findByIdAndUsername() generates different query cache keys');
$t->isnt($keys['find'], $keys['find_one_by_id_and_username'], '->find() and ->findOneByIdAndUsername() generates different query cache keys');
$t->isnt($keys['find'], $keys['find_by_username_and_password'], '->find() and ->findByUsernameAndPassword() generates different query cache keys');
$t->isnt($keys['find'], $keys['find_one_by_username_and_password'], '->find() and ->findOneByUsernameAndPassword() generates different query cache keys');

$t->isnt($keys['find_all'], $keys['find_by_id'], '->findAll() and ->findById() generates different query cache keys');
$t->isnt($keys['find_all'], $keys['find_one_by_id'], '->findAll() and ->findOneById() generates different query cache keys');
$t->isnt($keys['find_all'], $keys['find_by_id_and_username'], '->findAll() and ->findByIdAndUsername() generates different query cache keys');
$t->isnt($keys['find_all'], $keys['find_one_by_id_and_username'], '->findAll() and ->findOneByIdAndUsername() generates different query cache keys');
$t->isnt($keys['find_all'], $keys['find_by_username_and_password'], '->findAll() and ->findByUsernameAndPassword() generates different query cache keys');
$t->isnt($keys['find_all'], $keys['find_one_by_username_and_password'], '->findAll() and ->findOneByUsernameAndPassword() generates different query cache keys');

$t->isnt($keys['find_by_id'], $keys['find_one_by_id'], '->findById() and ->findOneById() generates different query cache keys');
$t->isnt($keys['find_by_id'], $keys['find_by_id_and_username'], '->findById() and ->findByIdAndUsername() generates different query cache keys');
$t->isnt($keys['find_by_id'], $keys['find_one_by_id_and_username'], '->findById() and ->findOneById() generates different query cache keys');
$t->isnt($keys['find_by_id'], $keys['find_by_username_and_password'], '->findById() and ->findByUsernameAndPassword() generates different query cache keys');
$t->isnt($keys['find_by_id'], $keys['find_one_by_username_and_password'], '->findById() and ->findOneByUsernameAndPassword() generates different query cache keys');

$t->isnt($keys['find_one_by_id'], $keys['find_by_id_and_username'], '->findOneById() and ->findByIdAndUsername() generates different query cache keys');
$t->isnt($keys['find_one_by_id'], $keys['find_one_by_id_and_username'], '->findOneById() and ->findOneByIdAndUsername() generates different query cache keys');
$t->isnt($keys['find_one_by_id'], $keys['find_by_username_and_password'], '->findOneById() and ->findByUsernameAndPassword() generates different query cache keys');
$t->isnt($keys['find_one_by_id'], $keys['find_one_by_username_and_password'], '->findOneById() and ->findOneByUsernameAndPassword() generates different query cache keys');

$t->isnt($keys['find_by_id_and_username'], $keys['find_one_by_id_and_username'], '->findByIdAndUsername() and ->findOneByIdAndUsername() generates different query cache keys');
$t->isnt($keys['find_by_id_and_username'], $keys['find_by_username_and_password'], '->findByIdAndUsername() and ->findByUsernameAndPassword() generates different query cache keys');
$t->isnt($keys['find_by_id_and_username'], $keys['find_one_by_username_and_password'], '->findByIdAndUsername() and ->findOneByUsernameAndPassword() generates different query cache keys');

$t->isnt($keys['find_one_by_id_and_username'], $keys['find_by_username_and_password'], '->findOneByIdAndUsername() and ->findByUsernameAndPassword() generates different query cache keys');
$t->isnt($keys['find_one_by_id_and_username'], $keys['find_one_by_username_and_password'], '->findOneByIdAndUsername() and ->findOneByUsernameAndPassword() generates different query cache keys');

$t->isnt($keys['find_by_username_and_password'], $keys['find_one_by_username_and_password'], '->findByUsernameAndPassword() and ->findOneByUsernameAndPassword() generates different query cache keys');
