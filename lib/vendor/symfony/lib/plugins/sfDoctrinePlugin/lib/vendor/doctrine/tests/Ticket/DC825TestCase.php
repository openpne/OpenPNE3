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
 * Doctrine_Ticket_DC825_TestCase
 *
 * @package     Doctrine
 * @author      Enrico Stahn <mail@enricostahn.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Ticket_DC825_TestCase extends Doctrine_UnitTestCase 
{
    public function prepareTables()
    {
        $this->tables[] = 'Ticket_DC825_Model';
        parent::prepareTables();
    }

    public function testTest()
    {
        Doctrine_Manager::getInstance()->setAttribute(Doctrine_Core::ATTR_USE_DQL_CALLBACKS, true);

        $user = new Ticket_DC825_Model();
        $user->username = 'jwage';
        $user->password = 'changeme';
        $user->save();
        $user->delete();

        $version = $user->getAuditLog()->getVersion($user, 2, Doctrine_Core::HYDRATE_RECORD);
	$versionTable = $version[0]->getTable();
	$versionTableColumns = $versionTable->getColumnNames();
	$recordTableColumns = $user->getTable()->getColumnNames();

        $this->assertFalse(in_array('id', $versionTableColumns));
        $this->assertTrue(in_array('model_id', $versionTableColumns));
        $this->assertTrue(count($versionTableColumns) == count($recordTableColumns));
        $this->assertTrue(count(array_diff($versionTableColumns, $recordTableColumns)) == 0);

        Doctrine_Manager::getInstance()->setAttribute(Doctrine_Core::ATTR_USE_DQL_CALLBACKS, false);
    }
}

class Ticket_DC825_Model extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('model_id as id', 'integer', null, array(
            'type' => 'integer',
            'unsigned' => false,
            'primary' => true,
            'autoincrement' => true,
        ));
        $this->hasColumn('username', 'string', 255);
        $this->hasColumn('password', 'string', 255);
    }

    public function setUp()
    {
        $this->actAs('Timestampable');
        $this->actAs('SoftDelete');
        $this->actAs('Versionable', array(
			'auditLog' => true,
        		'generateRelations' => false,
        		'deleteVersions' => false));
    }
}
