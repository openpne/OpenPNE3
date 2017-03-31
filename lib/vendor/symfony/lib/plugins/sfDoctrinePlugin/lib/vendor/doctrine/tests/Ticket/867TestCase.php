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
 * Doctrine_Ticket_867_TestCase
 *
 * @package     Doctrine
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Ticket_867_TestCase extends Doctrine_UnitTestCase 
{
  public function prepareTables()
  {
    $this->tables[] = 'T867_Section';
    parent::prepareTables();
  }
  
  public function testTicket()
  {
      try {
          $s = new T867_Section();
          $s->name = 'Test name';
          $s->Translation['en']->title = 'Test title';
          $s->Translation['en']->summary = 'Test summary';
          $s->save();
          
          $this->assertTrue($s->id > 0);
          $this->assertEqual($s->name, 'Test name');
          $this->assertEqual($s->Translation['en']->title, 'Test title');
          $this->assertEqual($s->Translation['en']->summary, 'Test summary');
          $this->assertEqual($s->Translation->getTable()->getOption('type'), $s->getTable()->getOption('type'));
          $this->assertEqual($s->Translation->getTable()->getOption('collate'), $s->getTable()->getOption('collate'));
          $this->assertEqual($s->Translation->getTable()->getOption('charset'), $s->getTable()->getOption('charset'));
          $this->pass();
      } catch (Exception $e) {
          $this->fail($e->getMessage());
      }
  }
}


class T867_Section extends Doctrine_Record
{
  public function setTableDefinition()
  {
    $this->setTableName('section');
    $this->hasColumn('id', 'integer', 4, array('primary' => true, 'autoincrement' => true));
    $this->hasColumn('name', 'string', 60, array('notnull' => true));
    $this->hasColumn('title', 'string', 60, array('notnull' => true));
    $this->hasColumn('summary', 'string', 255);
    
    $this->option('type', 'INNODB');
    $this->option('collate', 'utf8_unicode_ci');
    $this->option('charset', 'utf8');
  }
  
  public function setUp()
  {
    parent::setUp();
    $this->actAs('I18n', array('fields' =>  array(  0 => 'title',   1 => 'summary', ), 'className' => '%CLASS%_i18n'));
  }
}