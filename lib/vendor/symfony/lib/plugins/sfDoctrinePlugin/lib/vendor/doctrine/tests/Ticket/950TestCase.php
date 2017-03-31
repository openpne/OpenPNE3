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
 * Doctrine_Ticket_950_TestCase
 *
 * @package     Doctrine
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Ticket_950_TestCase extends Doctrine_UnitTestCase 
{
    public function testInit()
    {
        $this->dbh = new Doctrine_Adapter_Mock('mysql');
        $this->conn = Doctrine_Manager::getInstance()->openConnection($this->dbh);
    }

    public function testTest()
    {
        $sql = $this->conn->export->exportClassesSql(array('Ticket_950_AdresseRecord','Ticket_950_CountryRecord'));
        $this->assertEqual(count($sql), 3);
        $this->assertEqual($sql[0], 'CREATE TABLE country_record (id BIGINT NOT NULL AUTO_INCREMENT, iso VARCHAR(2) NOT NULL, name VARCHAR(80), printable_name VARCHAR(80), iso3 VARCHAR(3), numcode BIGINT, INDEX iso_idx (iso), PRIMARY KEY(id)) ENGINE = INNODB');
        $this->assertEqual($sql[1], 'CREATE TABLE adresse_record (id BIGINT NOT NULL AUTO_INCREMENT, adresse VARCHAR(255), cp VARCHAR(60), ville VARCHAR(255), pays VARCHAR(2), INDEX pays_idx (pays), PRIMARY KEY(id)) ENGINE = INNODB');
        $this->assertEqual($sql[2], 'ALTER TABLE adresse_record ADD CONSTRAINT adresse_record_pays_country_record_iso FOREIGN KEY (pays) REFERENCES country_record(iso)');
    }
}

class Ticket_950_AdresseRecord extends Doctrine_Record
{
	public function setTableDefinition()
	{
		$this->setTableName('adresse_record');
		$this->hasColumn('id', 'integer', 20, array('notnull' => true,
                                              'primary' => true,
                                              'autoincrement' => true));

		$this->hasColumn('adresse', 'string', 255);
		$this->hasColumn('cp', 'string', 60);
		$this->hasColumn('ville', 'string', 255);
		$this->hasColumn('pays', 'string', 2);
	}

	public function setUp()
	{
		$this->hasOne('Ticket_950_CountryRecord as Country', array('local' => 'pays', 'foreign' => 'iso'));
	}
}

class Ticket_950_CountryRecord extends Doctrine_Record
{
	public function setTableDefinition()
	{
		$this->setTableName('country_record');
		$this->hasColumn('id', 'integer', 11, array('notnull' => true,
                                              'primary' => true,
                                              'autoincrement' => true));

		$this->hasColumn('iso', 'string', 2, array('notnull' => true));

		$this->hasColumn('name', 'string', 80);
		$this->hasColumn('printable_name', 'string', 80);
		$this->hasColumn('iso3', 'string', 3);
		$this->hasColumn('numcode', 'integer', 10);
	}
}