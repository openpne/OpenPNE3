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
 * Doctrine_Ticket_987_TestCase
 *
 * @package     Doctrine
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Ticket_987_TestCase extends Doctrine_UnitTestCase 
{
  public function prepareTables()
  {
    $this->tables[] = 'Ticket_987_Person';
    parent::prepareTables();
  }

  public function testTest()
  {
    $person = new Ticket_987_Person();
    $person->gender = 'male';
    $person->firstname = 'jon';
    $person->lastname = 'wage';
    $person->save();

    // creating the query
    $q = Doctrine_Query::create();
    $q->from('Ticket_987_Person p');

    // creating the view
    $view = new Doctrine_View($q, 'view_person2person_type');
    $view->create();

    // creating the query
    $q = Doctrine_Query::create();
    $q->from('Ticket_987_Person p');

    // creating view object for querying
    $view = new Doctrine_View($q, 'view_person2person_type');

    // executes view
    $coll = $view->execute();

    $this->assertEqual($coll->count(), 1);
  }
}

class Ticket_987_Person extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('person');
        $this->hasColumn('id', 'integer', 11, array('primary' => true, 'notnull' => true, 'autoincrement' => true) );
        $this->hasColumn('gender', 'integer', 1, array('notblank' => true, 'primary' => false, 'notnull' => true, 'autoincrement' => false) );
        $this->hasColumn('firstname', 'string', 30, array('notblank' => true, 'primary' => false, 'notnull' => true, 'autoincrement' => false) );
        $this->hasColumn('lastname', 'string', 30, array('notblank' => true, 'primary' => false, 'notnull' => true, 'autoincrement' => false) );
    }
}