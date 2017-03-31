<?php
/*
 *  $Id: 1619TestCase.php 7490 2010-03-29 19:53:27Z jwage $
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
 * Doctrine_Ticket_1619_TestCase
 *
 * @package     Doctrine
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Ticket_1619_TestCase extends Doctrine_UnitTestCase {
	
	public function prepareTables()
  {
    $this->tables[] = 'Ticket_1619_Article';
    parent::prepareTables();
  }

	public function testTest()
  {
		$a = new Ticket_1619_Article();
		$a->Translation['fr']->name = 'article';
		$a->Translation['fr']->description = 'article';
		$a->Translation['en']->name = 'english article';
		$a->Translation['en']->description = 'english description';
		$a->save();
		
		$b = new Ticket_1619_Article();
		$a->Translation['fr']->name = 'maison';
		$a->Translation['fr']->description = 'habitation';
		$a->Translation['en']->name = 'english house';
		$a->Translation['en']->description = 'english big house';
		$a->save();
	}
}

class Ticket_1619_Article extends Doctrine_Record
{
	public function setTableDefinition()
  {
    $this->setTableName('article');
    $this->hasColumn('id', 'integer', 3, array('type' => 'integer', 'primary' => true, 'autoincrement' => true, 'length' => '3'));
    $this->hasColumn('name', 'string', 60, array('type' => 'string', 'length' => '60'));
    $this->hasColumn('description', 'string', 4000, array('type' => 'string', 'length' => '4000'));
  }

  public function setUp()
  {
    $i18n0 = new Doctrine_Template_I18n(array('fields' => array(0 => 'name', 1 => 'description')));
    $searchable1 = new Doctrine_Template_Searchable(array('fields' => array(0 => 'name')));
    $i18n0->addChild($searchable1);
    $this->actAs($i18n0);
  }
}
	

