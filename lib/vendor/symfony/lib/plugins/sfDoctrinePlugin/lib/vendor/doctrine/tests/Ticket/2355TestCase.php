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
 * Doctrine_Ticket_2355_TestCase
 *
 * @package     Doctrine
 * @author      Fabien Pennequin <fabien.pennequin@gmail.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Ticket_2355_TestCase extends Doctrine_UnitTestCase 
{
    public function prepareTables()
    {
        $this->tables[] = 'News';
        $this->tables[] = 'Episode';
        $this->tables[] = 'Writer';
        $this->tables[] = 'WriterEpisode';
        $this->tables[] = 'Director';
        $this->tables[] = 'DirectorEpisode';
        parent::prepareTables();
    }

    public function testImport()
    {
        $yml = <<<END
Director:
  david_nutter:
    name: David Nutter


Writer:
  alfred_gough:
    name: Alfred Gough
  miles_millar:
    name: Miles Millar


News:
  News_1:
    title: Date de retour de Smallville aux Etats-Unis

  News_2:
    title: Audiences de l'épisode 8.22 Doomsday aux Etats-Unis


Episode:
  Episode_101:
    season: 1
    number: 1
    title_us: Pilot
    title_fr: Bienvenue sur Terre
    Directors: [david_nutter]
    Writers: [alfred_gough, miles_millar]
END;
        try {
            file_put_contents('test.yml', $yml);
            Doctrine_Core::loadData('test.yml', true);

            $this->conn->clear();

            $query = new Doctrine_Query();
            $query->from('Episode e, e.Directors, e.Writers');

            $e = $query->execute();

            $this->assertEqual($e->count(), 1);
            $this->assertEqual($e[0]->season, 1);
            $this->assertEqual($e[0]->number, 1);
            $this->assertEqual($e[0]->title_us, 'Pilot');
            $this->assertEqual($e[0]->title_fr, 'Bienvenue sur Terre');
            $this->assertEqual($e[0]->Directors->count(), 1);
            $this->assertEqual($e[0]->Directors[0]->name, 'David Nutter');
            $this->assertEqual($e[0]->Writers->count(), 2);
            $this->assertEqual($e[0]->Writers[0]->name, 'Alfred Gough');
            $this->assertEqual($e[0]->Writers[1]->name, 'Miles Millar');

            $query = new Doctrine_Query();
            $query->from('News n');

            $n = $query->execute();

            $this->assertEqual($n->count(), 2);
            $this->assertEqual($n[0]->title, 'Date de retour de Smallville aux Etats-Unis');
            $this->assertEqual($n[1]->title, 'Audiences de l\'épisode 8.22 Doomsday aux Etats-Unis');

            $this->pass();
        } catch (Exception $e) {
            $this->fail();
        }

        unlink('test.yml');
    }
}

class News extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('title', 'string', 255, array(
             'type' => 'string',
             'notnull' => true,
             'notblank' => true,
             'length' => '255',
             ));
    }
}

class Episode extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('season', 'integer', 1, array(
             'type' => 'integer',
             'length' => 1,
             'notnull' => true,
             'notblank' => true,
             ));
        $this->hasColumn('number', 'integer', 1, array(
             'type' => 'integer',
             'length' => 1,
             'notnull' => true,
             'notblank' => true,
             ));
        $this->hasColumn('title_us', 'string', 100, array(
             'type' => 'string',
             'notnull' => true,
             'notblank' => true,
             'length' => '100',
             ));
        $this->hasColumn('title_fr', 'string', 100, array(
             'type' => 'string',
             'length' => '100',
             ));


        $this->index('episode', array(
             'fields' => 
             array(
              0 => 'season',
              1 => 'number',
             ),
             'type' => 'unique',
             ));
    }

    public function setUp()
    {
        $this->hasMany('Writer as Writers', array(
             'refClass' => 'WriterEpisode',
             'local' => 'episode_id',
             'foreign' => 'writer_id'));

        $this->hasMany('Director as Directors', array(
             'refClass' => 'DirectorEpisode',
             'local' => 'episode_id',
             'foreign' => 'director_id'));

        $this->hasMany('WriterEpisode', array(
             'local' => 'id',
             'foreign' => 'episode_id'));

        $this->hasMany('DirectorEpisode', array(
             'local' => 'id',
             'foreign' => 'episode_id'));
    }
}

class Writer extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('name', 'string', 150, array(
             'type' => 'string',
             'notnull' => true,
             'notblank' => true,
             'unique' => true,
             'length' => '150',
             ));
    }

    public function setUp()
    {
        $this->hasMany('Episode', array(
             'refClass' => 'WriterEpisode',
             'local' => 'writer_id',
             'foreign' => 'episode_id'));

        $this->hasMany('WriterEpisode', array(
             'local' => 'id',
             'foreign' => 'writer_id'));
    }
}

class WriterEpisode extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('episode_id', 'integer', null, array(
             'type' => 'integer',
             'primary' => true,
             ));
        $this->hasColumn('writer_id', 'integer', null, array(
             'type' => 'integer',
             'primary' => true,
             ));
    }

    public function setUp()
    {
        $this->hasOne('Writer', array(
             'local' => 'writer_id',
             'foreign' => 'id',
             'onDelete' => 'CASCADE'));

        $this->hasOne('Episode', array(
             'local' => 'episode_id',
             'foreign' => 'id',
             'onDelete' => 'CASCADE'));
    }
}

class Director extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('name', 'string', 150, array(
             'type' => 'string',
             'notnull' => true,
             'notblank' => true,
             'unique' => true,
             'length' => '150',
             ));
    }

    public function setUp()
    {
        $this->hasMany('Episode', array(
             'refClass' => 'DirectorEpisode',
             'local' => 'director_id',
             'foreign' => 'episode_id'));

        $this->hasMany('DirectorEpisode', array(
             'local' => 'id',
             'foreign' => 'director_id'));
    }
}

class DirectorEpisode extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('episode_id', 'integer', null, array(
             'type' => 'integer',
             'primary' => true,
             ));
        $this->hasColumn('director_id', 'integer', null, array(
             'type' => 'integer',
             'primary' => true,
             ));
    }

    public function setUp()
    {
        $this->hasOne('Director', array(
             'local' => 'director_id',
             'foreign' => 'id',
             'onDelete' => 'CASCADE'));

        $this->hasOne('Episode', array(
             'local' => 'episode_id',
             'foreign' => 'id',
             'onDelete' => 'CASCADE'));
    }
}