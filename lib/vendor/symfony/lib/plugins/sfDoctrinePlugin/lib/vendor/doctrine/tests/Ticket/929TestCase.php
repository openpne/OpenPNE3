<?php
/*
 *  $Id$
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the LGPL. For more information, see
 * <http://www.doctrine-project.org>.
 */

/**
 * Doctrine_Ticket_929_TestCase
 *
 * @package     Doctrine
 * @author      David Stendardi <david.stendardi@adenclassifieds.com>
 * @category    Hydration
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Ticket_929_TestCase extends Doctrine_UnitTestCase 
{
    public function prepareData()
    {	   
        $oPerson = new T929_Person;
        $oPerson->name = 'Jonathan';
        $oPerson->Country->code = 'us';
        $oPerson->Country->Translation['fr']->name = 'Etats Unis';
        $oPerson->Country->Translation['en']->name = 'United states';         
        $oPerson->save();

        parent::prepareData();
    }
 
    public function prepareTables()
    {
        $this->tables = array();
        $this->tables[] = 'T929_Person';
        $this->tables[] = 'T929_Country';
        $this->tables[] = 'T929_JobPosition';
        $this->tables[] = 'T929_JobCategory';

        parent::prepareTables();
    }
  
    public function testTicket()
    {
        try { 
            $q = new Doctrine_Query();
            $r = $q
                ->from('T929_Person P')
                ->leftJoin('P.Country Ct')
                ->leftJoin('Ct.Translation T1 WITH T1.lang = ?', 'fr')
                ->leftJoin('P.JobPositions J')
                ->leftJoin('J.Category C')
                ->leftJoin('C.Translation T2 WITH T2.lang = ?', 'fr')
                ->where('P.name = ?', 'Jonathan')
                ->fetchOne();
        } catch (Exception $e) {
            $this->fail($e->getMessage());        
        }
    }
}

class T929_Person extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('T929_person');
        $this->hasColumn('country_id', 'integer');
        $this->hasColumn('name', 'string', 200);
    }

    public function setUp()
    {
        parent::setUp();

        $this->hasOne('T929_Country as Country', array(
            'local' => 'country_id',
            'foreign' => 'id',
            'onDelete' => 'CASCADE'
        ));

        $this->hasMany('T929_JobPosition as JobPositions', array(
            'local' => 'id',
            'foreign' => 'person_id',
            'onDelete' => 'CASCADE'
        ));
    }
}

class T929_Country extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('T929_country');
        $this->hasColumn('name', 'string', 200);
        $this->hasColumn('code', 'string', 200);
    }

    public function setUp()
    {
        parent::setUp();

        $this->hasMany('T929_Person as Persons', array(
            'local' => 'id',
            'foreign' => 'country_id',
            'onDelete' => 'CASCADE'
        ));

        $this->actAs('I18n', array('fields' => array('name')));
    }
}

class T929_JobPosition extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('T929_address');
        $this->hasColumn('name', 'string', 200);
        $this->hasColumn('person_id', 'integer');
        $this->hasColumn('job_category_id', 'integer');
    }

    public function setUp()
    {
        parent::setUp();

        $this->hasOne('T929_Person as Person', array(
            'local' => 'person_id',
            'foreign' => 'id',
            'onDelete' => 'CASCADE'
        ));

        $this->hasOne('T929_JobCategory as Category', array(
            'local' => 'job_category_id',
            'foreign' => 'id',
            'onDelete' => 'CASCADE'
        ));
    }
}

class T929_JobCategory extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('job_category');
        $this->hasColumn('code', 'integer', 4);
        $this->hasColumn('name', 'string', 200);
    }

    public function setUp()
    {
        parent::setUp();

        $this->hasMany('T929_JobPosition as Positions', array(
            'local' => 'id',
            'foreign' => 'job_category_id',
            'onDelete' => 'CASCADE'
        ));

        $this->actAs('I18n', array('fields' => array('name')));
    }
}