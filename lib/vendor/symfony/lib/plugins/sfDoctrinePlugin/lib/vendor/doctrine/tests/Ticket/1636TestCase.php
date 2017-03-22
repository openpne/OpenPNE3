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
 * Doctrine_Ticket_1636_TestCase
 *
 * @package     Doctrine
 * @author      Eugene Janusov <esycat@gmail.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Ticket_1636_TestCase extends Doctrine_UnitTestCase
{
    private $resultCacheLifeSpan = 5;

    public function prepareTables()
    {
        $this->tables = array();
        $this->tables[] = 'Ticket_1636_File';
        $this->tables[] = 'Ticket_1636_FileType';
        parent::prepareTables();
    }

    public function prepareData()
    {
        for ($i = 1; $i <= 2; $i++) {
            $fileType = new Ticket_1636_FileType();
            $fileType->id = $i;
            $fileType->name = 'Type ' . $i;
            $fileType->save();
        }

        for ($i = 1; $i <= 10; $i++) {
            $file = new Ticket_1636_File();
            $file->id = $i;
            $file->type_id = 1;
            $file->filename = 'File ' . $i;
            $file->save();
        }
    }

    public function testResultCacheShouldStoreRelatedComponentsData()
    {
        // Profiler
        $profiler = new Doctrine_Connection_Profiler();
        $this->conn->setListener($profiler);

        $cacheDriver = new Doctrine_Cache_Array();

        $query = Doctrine_Query::create()
            ->useResultCache($cacheDriver, $this->resultCacheLifeSpan)
            ->from('Ticket_1636_File f')
            ->innerJoin('f.type t')
            ->where('f.type_id = ?')
            ->orderBy('f.id DESC')
            ->limit(2);

        // Execute query first time.
        // Results should be placed into memcache.
        $files = $query->execute(array(1));

        // Execute query second time.
        // Results should be getted from memcache.
        $files = $query->execute(array(1));

        if (count($files))
            foreach ($files as $file)
                $justForTest = $file->type->id;

        $executeQueryCount = 0;

        foreach ($profiler as $event) {
            if ($event->getName() == 'execute') {
                $executeQueryCount++;
                //echo $event->getQuery(), "\n";
            }
        }

        // It should be only one really executed query.
        $this->assertEqual($executeQueryCount, 1);
    }
}

class Ticket_1636_FileType extends Doctrine_Record {

    public function setTableDefinition()
    {
        static $columns = array(
            'id' => array(
                'type' => 'integer',
                'length' => 4,
                'unsigned' => true,
                'notnull' => true,
                'primary' => true,
                'autoinc' => true
            ),
            'name' => array(
                'type' => 'string',
                'length' => 32,
                'notnull' => true
            )
        );

        $this->setTableName('files_types');
        $this->hasColumns($columns);
    }

    public function setUp()
    {
        $this->hasMany('Ticket_1636_File as files', array(
            'local' => 'id',
            'foreign' => 'type_id'
        ));
    }

}

class Ticket_1636_File extends Doctrine_Record {

    public function setTableDefinition()
    {
        static $columns = array(
            'id' => array(
                'type' => 'integer',
                'length' => 10,
                'unsigned' => true,
                'notnull' => true,
                'primary' => true,
                'autoinc' => true
            ),
            'type_id' => array(
                'type' => 'integer',
                'length' => 4,
                'notnull' => true
            ),
            'filename' => array(
                'type' => 'string',
                'length' => 255,
                'notnull' => true
            )
        );

        $this->setTableName('files');
        $this->hasColumns($columns);
    }

    public function setUp()
    {
        $this->hasOne('Ticket_1636_FileType as type', array(
            'local' => 'type_id',
            'foreign' => 'id'
        ));
    }

}