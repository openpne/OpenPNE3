<?php
include_once dirname(__FILE__) . '/../../bootstrap/unit.php';
include_once dirname(__FILE__) . '/../../bootstrap/database.php';
sfContext::createInstance($configuration);

$t = new lime_test(15, new lime_output_color());

//==============================================================================
$t->diag('opActivityQueryBuilder::create()');

$builder = opActivityQueryBuilder::create();
$t->cmp_ok($builder, 'instanceof', 'opActivityQueryBuilder', '::create() returns opActivityQueryBuilder instance.');

$builder2 = opActivityQueryBuilder::create();
$t->cmp_ok($builder2, '!==', $builder, 'opActivityQueryBuilder is NOT singleton.');


//==============================================================================
$t->diag('opActivityQueryBuilder::buildQuery()');

$query = opActivityQueryBuilder::create()->buildQuery();
$t->cmp_ok($query, 'instanceof', 'Doctrine_Query', '->buildQuery() returns Doctrine_Query instance.');

$query->free();


//==============================================================================
$t->diag('opActivityQueryBuilder::includeSelf()');

$builder = opActivityQueryBuilder::create()
  ->setViewerId(1)
  ->includeSelf();

$query = $builder->buildQuery();
$result = $query->execute();

$t->is($result->getPrimaryKeys(), array(8, 7, 6, 5, 4, 3, 2, 1), '->includeSelf() viewerId = 1');

$result->free(true);
$query->free();

//------------------------------------------------------------
$builder = opActivityQueryBuilder::create()
  ->setViewerId(2)
  ->includeSelf();

$query = $builder->buildQuery();
$result = $query->execute();

$t->is($result->getPrimaryKeys(), array(), '->includeSelf() viewerId = 2');

$result->free(true);
$query->free();


//==============================================================================
$t->diag('opActivityQueryBuilder::includeFriends()');

$builder = opActivityQueryBuilder::create()
  ->setViewerId(1)
  ->includeFriends();

$query = $builder->buildQuery();
$result = $query->execute();

$t->is($result->getPrimaryKeys(), array(1055), '->includeFriends() viewerId = 1');

$result->free(true);
$query->free();

//------------------------------------------------------------
$builder = opActivityQueryBuilder::create()
  ->setViewerId(2)
  ->includeFriends();

$query = $builder->buildQuery();
$result = $query->execute();

$t->is($result->getPrimaryKeys(), array(8, 7, 6, 5, 3, 2, 1), '->includeFriends() viewerId = 2');

$result->free(true);
$query->free();


//==============================================================================
$t->diag('opActivityQueryBuilder::includeSns()');

$builder = opActivityQueryBuilder::create()
  ->includeSns();

$query = $builder->buildQuery();
$result = $query->execute();

$t->is($result->getPrimaryKeys(), array(1056, 1055, 8, 7, 6, 5, 2, 1));

$result->free(true);
$query->free();


//==============================================================================
$t->diag('opActivityQueryBuilder::includeMember()');

$builder = opActivityQueryBuilder::create()
  ->setViewerId(1)
  ->includeMember(1);

$query = $builder->buildQuery();
$result = $query->execute();

$t->is($result->getPrimaryKeys(), array(8, 7, 6, 5, 4, 3, 2, 1), '->includeMember(1) viewerId = 1');

$result->free(true);
$query->free();

//------------------------------------------------------------
$builder = opActivityQueryBuilder::create()
  ->setViewerId(2)
  ->includeMember(1);

$query = $builder->buildQuery();
$result = $query->execute();

$t->is($result->getPrimaryKeys(), array(8, 7, 6, 5, 3, 2, 1), '->includeMember(1) viewerId = 2 (first_member is friend)');

$result->free(true);
$query->free();

//------------------------------------------------------------
$builder = opActivityQueryBuilder::create()
  ->setViewerId(3)
  ->includeMember(1);

$query = $builder->buildQuery();
$result = $query->execute();

$t->is($result->getPrimaryKeys(), array(8, 7, 6, 5, 2, 1), '->includeMember(1) viewerId = 3 (first_member is not friend)');

$result->free(true);
$query->free();


//==============================================================================
$t->diag('opActivityQueryBuilder multiple conditions');

$builder = opActivityQueryBuilder::create()
  ->setViewerId(1)
  ->includeSelf()
  ->includeFriends();

$query = $builder->buildQuery();
$result = $query->execute();

$t->is($result->getPrimaryKeys(), array(1055, 8, 7, 6, 5, 4, 3, 2, 1), '->includeSelf()->includeFriends() viewerId = 1');

$result->free(true);
$query->free();

//------------------------------------------------------------
$builder = opActivityQueryBuilder::create()
  ->setViewerId(2)
  ->includeSelf()
  ->includeFriends();

$query = $builder->buildQuery();
$result = $query->execute();

$t->is($result->getPrimaryKeys(), array(8, 7, 6, 5, 3, 2, 1), '->includeSelf()->includeFriends() viewerId = 2');

$result->free(true);
$query->free();

//------------------------------------------------------------
$builder = opActivityQueryBuilder::create()
  ->setViewerId(1)
  ->includeSelf()
  ->includeFriends()
  ->includeSns();

$query = $builder->buildQuery();
$result = $query->execute();

$t->is($result->getPrimaryKeys(), array(1056, 1055, 8, 7, 6, 5, 4, 3, 2, 1), '->includeSelf()->includeFriends()->includeSns() viewerId = 1');

$result->free(true);
$query->free();

//------------------------------------------------------------
$builder = opActivityQueryBuilder::create()
  ->setViewerId(2)
  ->includeSelf()
  ->includeFriends()
  ->includeSns();

$query = $builder->buildQuery();
$result = $query->execute();

$t->is($result->getPrimaryKeys(), array(1056, 1055, 8, 7, 6, 5, 3, 2, 1), '->includeSelf()->includeFriends()->includeSns() viewerId = 2');

$result->free(true);
$query->free();

