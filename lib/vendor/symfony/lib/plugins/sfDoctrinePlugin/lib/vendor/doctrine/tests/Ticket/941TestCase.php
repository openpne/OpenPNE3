<?php

/**
 * @author Donald Ball
 */
class Doctrine_Ticket_941_TestCase extends Doctrine_UnitTestCase
{

  public function prepareTables()
  {
    $this->tables = array('Site', 'Variable', 'SiteVarvalue');
    parent::prepareTables();
  }

  public function prepareData()
  {
      $site = new Site();     
      $site->site_id      = 1;
      $site->site_domain  = 'site1'; 
      $site->save();

      $site = new Site();     
      $site->site_id      = 2;
      $site->site_domain  = 'site2'; 
      $site->save();

      $var  = new Variable(); 
      $var->variable_id   = 1;
      $var->variable_name = 'var1'; 
      $var->save();

      $var  = new Variable(); 
      $var->variable_id   = 2;
      $var->variable_name = 'var2'; 
      $var->save();

      $varval = new SiteVarvalue();
      $varval->site_id        = 1;
      $varval->variable_id    = 1;
      $varval->varvalue_value = 'val1 dom1 var1';
      $varval->save();

      $varval = new SiteVarvalue();
      $varval->site_id        = 1;
      $varval->variable_id    = 2;
      $varval->varvalue_value = 'val2 dom1 var2';
      $varval->save();

      $varval = new SiteVarvalue();
      $varval->site_id        = 2;
      $varval->variable_id    = 1;
      $varval->varvalue_value = 'val3 dom2 var1';
      $varval->save();

      $varval = new SiteVarvalue();
      $varval->site_id        = 2;
      $varval->variable_id    = 2;
      $varval->varvalue_value = 'val4 dom2 var2';
      $varval->save();
  }

  public function testTicket()
  {
      $query = new Doctrine_Query();
      $query = $query->from('Site s LEFT JOIN s.Variables v LEFT JOIN v.Values vv WITH vv.site_id = s.site_id');

      $sites = $query->execute();
      
      $this->assertEqual('site1', $sites[0]->site_domain);
      $this->assertEqual(2, count($sites));
      
      // this is important for the understanding of the behavior
      $this->assertIdentical($sites[0]->Variables[0], $sites[1]->Variables[0]);
      $this->assertIdentical($sites[0]->Variables[1], $sites[1]->Variables[1]);
      $this->assertEqual(2, count($sites[0]->Variables[0]->Values));
      $this->assertEqual(2, count($sites[1]->Variables[0]->Values));
      $this->assertEqual(2, count($sites[0]->Variables[1]->Values));
      $this->assertEqual(2, count($sites[1]->Variables[1]->Values));
      // Here we see that there can be only one Values on each Variable object. Hence
      // they end up with 2 objects each.
      $this->assertEqual('val1 dom1 var1', $sites[0]->Variables[0]->Values[0]->varvalue_value);
      $this->assertEqual('val3 dom2 var1', $sites[0]->Variables[0]->Values[1]->varvalue_value);
      $this->assertEqual('val2 dom1 var2', $sites[0]->Variables[1]->Values[0]->varvalue_value);
      $this->assertEqual('val4 dom2 var2', $sites[0]->Variables[1]->Values[1]->varvalue_value);
      
      $this->assertEqual('var1', $sites[0]->Variables[0]->variable_name);
      $this->assertEqual('var1', $sites[1]->Variables[0]->variable_name);
      
      $this->assertEqual('var2', $sites[0]->Variables[1]->variable_name);
      $this->assertEqual('var2', $sites[1]->Variables[1]->variable_name);
      
      
      // now array hydration
      
      $sites = $query->fetchArray();
      
      $this->assertEqual('site1', $sites[0]['site_domain']);
      $this->assertEqual('site2', $sites[1]['site_domain']);
      $this->assertEqual(2, count($sites));
      
      // this is important for the understanding of the behavior
      $this->assertEqual(1, count($sites[0]['Variables'][0]['Values']));
      $this->assertEqual(1, count($sites[1]['Variables'][0]['Values']));
      $this->assertEqual(1, count($sites[0]['Variables'][1]['Values']));
      $this->assertEqual(1, count($sites[1]['Variables'][1]['Values']));
      // Here we see that the Values collection of the *same* Variable object can have
      // different contents when hydrating arrays
      $this->assertEqual('val1 dom1 var1', $sites[0]['Variables'][0]['Values'][0]['varvalue_value']);
      $this->assertEqual('val3 dom2 var1', $sites[1]['Variables'][0]['Values'][0]['varvalue_value']);
      // Here we see that the Values collection of the *same* Variable object can have
      // different contents when hydrating arrays
      $this->assertEqual('val2 dom1 var2', $sites[0]['Variables'][1]['Values'][0]['varvalue_value']);
      $this->assertEqual('val4 dom2 var2', $sites[1]['Variables'][1]['Values'][0]['varvalue_value']);
      
      $this->assertEqual('var1', $sites[0]['Variables'][0]['variable_name']);
      $this->assertEqual('var1', $sites[1]['Variables'][0]['variable_name']);
      
      $this->assertEqual('var2', $sites[0]['Variables'][1]['variable_name']);
      $this->assertEqual('var2', $sites[1]['Variables'][1]['variable_name']);
      
  }

}

abstract class BaseSite extends Doctrine_Record
{

  public function setTableDefinition()
  {
    $this->setTableName('_site');
    $this->hasColumn('site_id', 'integer', 4, array('notnull' => true, 'primary' => true, 'autoincrement' => true));
    $this->hasColumn('site_domain', 'string', 255, array('notnull' => true));
  }

  public function setUp()
  {
    parent::setUp();
    $this->hasMany('Variable as Variables', array('refClass' => 'SiteVarvalue',
                                                  'local' => 'site_id',
                                                  'foreign' => 'variable_id'));
  }

}
abstract class BaseVariable extends Doctrine_Record
{

  public function setTableDefinition()
  {
    $this->setTableName('_variable');
    $this->hasColumn('variable_id', 'integer', 4, array('notnull' => true, 'primary' => true, 'autoincrement' => true));
    $this->hasColumn('variable_name', 'string', 100, array('notnull' => true));
  }

  public function setUp()
  {
    parent::setUp();
    $this->hasMany('Site as Sites', array('refClass' => 'SiteVarvalue',
                                          'local' => 'variable_id',
                                          'foreign' => 'site_id'));

    $this->hasMany('SiteVarvalue as Values', array('local' => 'variable_id',
                                                    'foreign' => 'variable_id'));
  }

}
abstract class BaseSiteVarvalue extends Doctrine_Record
{

  public function setTableDefinition()
  {
    $this->setTableName('_site_varvalue');
    $this->hasColumn('varvalue_id', 'integer', 4, array('notnull' => true, 'primary' => true, 'autoincrement' => true));
    $this->hasColumn('site_id', 'integer', 4, array('notnull' => true));
    $this->hasColumn('variable_id', 'integer', 4, array('notnull' => true));
    $this->hasColumn('varvalue_value', 'string', null, array('notnull' => true));
  }

  public function setUp()
  {
    parent::setUp();
    $this->hasOne('Variable as Variables', array('local' => 'variable_id',
                                                 'foreign' => 'variable_id'));
  }

}
class Site extends BaseSite
{
}
class Variable extends BaseVariable
{
}
class SiteVarvalue extends BaseSiteVarvalue
{
}
