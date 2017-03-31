<?php
/*
 * Created on Jun 26, 2008
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

class Doctrine_Ticket_1175_TestCase extends Doctrine_UnitTestCase
{
	public function prepareTables()
    {
        $this->tables[] = 'gImage';

        $this->tables[] = 'gUser';
        $this->tables[] = 'gUserImage';
        $this->tables[] = 'gUserFile';

        $this->tables[] = 'gBlog';
        $this->tables[] = 'gBlogImage';
        $this->tables[] = 'gBlogFile';

        parent::prepareTables();
    }

    public function testLeftJoinToInheritanceChildTable()
    {
        $u = new gUser();
        $u->first_name = 'Some User';

        $img = new gUserImage();
        $img->filename = 'user image 1';
        $u->Images[] = $img;
        
        $img = new gUserImage();
        $img->filename = 'user image 2';
        $u->Images[] = $img;
        $u->save();

        $b = new gBlog();
        $b->title = 'First Blog';

        $img = new gBlogImage();
        $img->filename = 'blog image 1';
        $b->Images[] = $img;

        $img = new gBlogFile();
        $img->filename = 'blog file 1';
        $b->Files[] = $img;

        $b->save();

        $q = Doctrine_Query::create() 
                    ->from('gUser u') 
                    ->leftJoin('u.Images i') 
                    ->leftJoin('u.Files f') 
                    ->where('u.id = ?', array(1)); 
   
        $this->assertEqual($q->getSqlQuery(), 'SELECT g.id AS g__id, g.first_name AS g__first_name, g.last_name AS g__last_name, g2.id AS g2__id, g2.owner_id AS g2__owner_id, g2.filename AS g2__filename, g2.otype AS g2__otype, g3.id AS g3__id, g3.owner_id AS g3__owner_id, g3.filename AS g3__filename, g3.otype AS g3__otype FROM g_user g LEFT JOIN g_image g2 ON g.id = g2.owner_id AND g2.otype = 1 LEFT JOIN g_file g3 ON g.id = g3.owner_id AND g3.otype = 1 WHERE (g.id = ?)'); 
     
        $u = $q->fetchOne(); 

        $this->assertTrue( is_object($u) );
        if (is_object($u)) {
            $this->assertEqual(count($u->Images),2);
        } else {
            $this->fail();
        }
    }
}

class gImage extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('id', 'integer', 4, array('primary' => true, 'autoincrement' => true));
        $this->hasColumn('owner_id', 'integer', 4);
        $this->hasColumn('filename', 'string', 64);
        $this->hasColumn('otype','integer',4);
        $this->setAttribute(Doctrine_Core::ATTR_EXPORT, Doctrine_Core::EXPORT_ALL ^ Doctrine_Core::EXPORT_CONSTRAINTS);

        $this->setSubClasses(array('gUserImage' => array('otype' => 1),'gBlogImage' => array('otype' => 2)));
    }
}

class gUserImage extends gImage
{
    public function setUp()
    {
        parent::setUp();
        $this->hasOne('gUser as User', array('local' => 'owner_id','foreign' => 'id'));
    }
}

class gBlogImage extends gImage
{
    public function setUp()
    {
        parent::setUp();
        $this->hasOne('gBlog as Blog', array('local' => 'owner_id','foreign' => 'id'));
    }
	
}

class gFile extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('id', 'integer', 4, array('primary' => true, 'autoincrement' => true));
        $this->hasColumn('owner_id', 'integer', 4);
        $this->hasColumn('filename', 'string', 64);
        $this->hasColumn('otype','integer',4);
        $this->setAttribute(Doctrine_Core::ATTR_EXPORT, Doctrine_Core::EXPORT_ALL ^ Doctrine_Core::EXPORT_CONSTRAINTS);

        $this->setSubClasses(array('gUserFile' => array('otype' => 1),'gBlogFile' => array('otype' => 2)));
    }
}

class gUserFile extends gFile
{
    public function setUp()
    {
        parent::setUp();
        $this->hasOne('gUser as User', array('local' => 'owner_id','foreign' => 'id'));
    }
}

class gBlogFile extends gFile
{
    public function setUp()
    {
        parent::setUp();
        $this->hasOne('gBlog as Blog', array('local' => 'owner_id','foreign' => 'id'));
    }
}

class gBlog extends Doctrine_Record
{
	public function setTableDefinition()
    {
        $this->hasColumn('id', 'integer', 4, array('primary' => true, 'autoincrement' => true));
        $this->hasColumn('title', 'string', 128);
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasMany('gBlogImage as Images', array('local' => 'id','foreign' => 'owner_id'));
        $this->hasMany('gBlogFile as Files', array('local' => 'id','foreign' => 'owner_id'));
    }

}

class gUser extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('id', 'integer', 4, array('primary' => true, 'autoincrement' => true));
        $this->hasColumn('first_name', 'string', 128);
        $this->hasColumn('last_name', 'string', 128);
    }    

    public function setUp()
    {
        parent::setUp();
        $this->hasMany('gUserImage as Images', array('local' => 'id','foreign' => 'owner_id'));
        $this->hasMany('gUserFile as Files', array('local' => 'id','foreign' => 'owner_id'));
    }
}
