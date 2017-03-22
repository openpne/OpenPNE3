<?php
/*
 *  $Id: 2229TestCase.php 7490 2010-03-29 19:53:27Z jwage $
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
 * Doctrine_Ticket_2xxx_TestCase
 *
 * @package     Doctrine
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Ticket_2229_TestCase extends Doctrine_UnitTestCase 
{
  public function prepareTables()
  {
    $this->tables[] = 'Ticket_2229_SlugBug';
    parent::prepareTables();
  }
  
  public function testTicket()
  {
    $d = new Ticket_2229_SlugBug();
    $d->name = 'String with UpperLowerCase';
    $d->save();
    $this->assertEqual($d->slug, 'string-with-upperlowercase');
    
    $d = new Ticket_2229_SlugBug();
    $d->name = 'Custom name OPACs';
    $d->save();
    $this->assertEqual($d->slug, 'custom-name-opacs');
    
    $d = new Ticket_2229_SlugBug();
    $d->name = 'Présentation unifiée OPACs';
    $d->save();
    $this->assertEqual($d->slug, 'presentation-unifiee-opacs');
  }
}


class Ticket_2229_SlugBug extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('SlugBug');
        $this->hasColumn('id', 'integer', 11, array('primary' => true, 'notnull' => true, 'autoincrement' => true));
        $this->hasColumn('name', 'string');
    }
    
    public function setUp()
    {
      parent::setUp();
      $this->actAs('Sluggable', array('unique' => true,
                                      'fields' => array('name')));
    }
}
