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
 * Doctrine_Ticket_1304_TestCase
 *
 * @package     Doctrine
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Ticket_1304_TestCase extends Doctrine_UnitTestCase {
  public function prepareTables() {
    $this->tables[] = 'Doctrine_Ticket_1304_Slug';
    parent::prepareTables();
  }
  
  public function testTicket()
  {
	// run 1
     try {
        $r = new Doctrine_Ticket_1304_Slug();
        $r->Translation['en']->title	= 'Title';
        $r->Translation['en']->content	= 'Content';
        $r->save();
      } catch (Exception $e) {
          $this->fail($e->getMessage());
      }
      $this->assertEqual('title', $r->Translation['en']->slug);

	// run 2
     try {
        $r = new Doctrine_Ticket_1304_Slug();
        $r->Translation['en']->title	= 'Title';
        $r->Translation['en']->content	= 'Content';
        $r->save();
      } catch (Exception $e) {
          $this->fail($e->getMessage());
      }
      $this->assertEqual('title-1', $r->Translation['en']->slug);

	// run 3
     try {
        $r = new Doctrine_Ticket_1304_Slug();
        $r->Translation['en']->title	= 'Title';
        $r->Translation['en']->content	= 'Content';
        $r->save();
      } catch (Exception $e) {
          $this->fail($e->getMessage());
      }
      $this->assertEqual('title-2', $r->Translation['en']->slug);
  }
}

class Doctrine_Ticket_1304_Slug extends Doctrine_Record
{
  public function setTableDefinition()
  {
    $this->hasColumn('title', 'string', 255, array('type' => 'string', 'length' => '255'));
    $this->hasColumn('content', 'string', null, array('type' => 'string'));
  }

  public function setUp()
  {
    $i18n0 = new Doctrine_Template_I18n(array('fields' => array(0 => 'title', 1 => 'content')));
    $sluggable1 = new Doctrine_Template_Sluggable(array('fields' => array(0 => 'title'), 'indexName' => 'i18n_sluggable_test'));
    $i18n0->addChild($sluggable1);
    $this->actAs($i18n0);
  }
}