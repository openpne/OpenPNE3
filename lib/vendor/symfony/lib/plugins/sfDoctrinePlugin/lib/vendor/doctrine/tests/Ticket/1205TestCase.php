<?php
class Doctrine_Ticket_1205_TestCase extends Doctrine_UnitTestCase
{        
    public function prepareData()
    { 
        $user = new Ticket1205TestUser();
        $user->id = 1;
        $user->first_name = 'Slick';
        $user->last_name = 'Rick';
        $user->save();
  
        $address = new Ticket1205TestAddress();
        $address->id = 1;
        $address->user_id = 1;
        $address->city = 'Anywhere';
        $address->save();    
    }    
    
    public function prepareTables()
    {
        $this->tables[] = 'Ticket1205TestUser';
        $this->tables[] = 'Ticket1205TestAddress';
        parent::prepareTables();
    }    
    
    public function testTicket()
    {
        try {
          // Each Address has 1 User
          $q = Doctrine_Query::create()
                ->from('Ticket1205TestAddress a')
                ->innerjoin('a.User u')
                ->execute(array(), Doctrine_Core::HYDRATE_ARRAY);
          $this->fail();
        } catch (Exception $e) {
          $this->pass();
        }
    }
}

class Ticket1205HydrationListener extends Doctrine_Record_Listener
{
    public function postHydrate(Doctrine_Event $event)
    {
        throw new Exception('This is called so we are successfull!');
    }
}

class Ticket1205TestUser extends Doctrine_Record
{
  public function setTableDefinition()
  {
    $this->setTableName('ticket1205_user');
    $this->hasColumn('first_name', 'string', 31);
    $this->hasColumn('last_name', 'string', 31);
  }

  public function setUp()
  {
    $this->addListener(new Ticket1205HydrationListener());
    $this->hasMany('Ticket1205TestAddress as Addresses', array('local'   => 'id',
                                                               'foreign' => 'user_id'));
  }
}

class Ticket1205TestAddress extends Doctrine_Record
{
  public function setTableDefinition()
  {
    $this->setTableName('ticket1205_address');
    $this->hasColumn('user_id', 'integer', 4, array('notnull' => true));
    $this->hasColumn('city', 'string', 31);
  }

  public function setUp()
  {    
    $this->hasOne('Ticket1205TestUser as User', array('local'   => 'user_id',
                                                      'foreign' => 'id'));
  }
}