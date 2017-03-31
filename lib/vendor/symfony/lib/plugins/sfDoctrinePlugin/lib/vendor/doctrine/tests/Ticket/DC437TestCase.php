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
 * Doctrine_Ticket_DC437_TestCase
 *
 * @package     Doctrine
 * @author      Eugene Janusov <esycat@gmail.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Ticket_DC437_TestCase extends Doctrine_UnitTestCase
{
    private function prepareConnections()
    {
    	// Establish two new individual connections
        $dsn = 'sqlite::memory:';
        $dbh = new PDO($dsn);
        $this->manager->openConnection($dbh, 'conn1', false);

        $dsn = 'sqlite::memory:';
        $dbh = new PDO($dsn);
        $this->manager->openConnection($dbh, 'conn2', false);
    }

    public function prepareTables()
    {
        // Don't see any better place to perform connection preparation
        $this->prepareConnections();

        $this->tables = array();
        $this->tables[] = 'Doctrine_Ticket_DC437_Record';

        /* Export classes for each of the existing connections.
         *
         * To trick Doctrine_Export::exportClasses() implementation to use
         * a proper connection, we need to manually re-bind all the components.
         */
        foreach (array('conn1', 'conn2') as $dbConnName) {
            $dbConn = $this->manager->getConnection($dbConnName);
        	foreach ($this->tables as $componentName) {
        	    $this->manager->bindComponent($componentName, $dbConn->getName());
        	}
        	$dbConn->export->exportClasses($this->tables);
        }
    }

    public function prepareData()
    {
    	$conn1 = $this->manager->getConnection('conn1');
        $conn2 = $this->manager->getConnection('conn2');

        // Create 1 record using conn1, and 2 records using conn2
        $r1 = new Doctrine_Ticket_DC437_Record();
        $r1->save($conn1);

        $r2 = new Doctrine_Ticket_DC437_Record();
        $r2->save($conn2);
        $r3 = new Doctrine_Ticket_DC437_Record();
        $r3->save($conn2);
    }

    public function testConnectionQuery()
    {
        /* Let's retrieve all the records twice using different connections.
         *
         * Since we have created a different number of records for different
         * connections (databases), we expect to get different values here.
         *
         * But due to the bug both queries will be performed using the same
         * connection.
         */

    	$conn1 = $this->manager->getConnection('conn1');
        $conn2 = $this->manager->getConnection('conn2');

        $dql = 'FROM Doctrine_Ticket_DC437_Record';

        $rs1 = $conn1->query($dql);
        $rs2 = $conn2->query($dql);

        $this->assertNotEqual(count($rs1), count($rs2));
    }
}

class Doctrine_Ticket_DC437_Record extends Doctrine_Record {

    public function setTableDefinition()
    {
        $this->setTableName('dc437records');

        $this->hasColumn('id', 'integer', 5, array(
            'unsigned'      => true,
            'notnull'       => true,
            'primary'       => true,
            'autoincrement' => true
        ));

        $this->hasColumn('test', 'string');
    }
}