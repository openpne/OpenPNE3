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
 * Doctrine_Ticket_1254_TestCase
 *
 * @package     Doctrine
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Ticket_1254_TestCase extends Doctrine_UnitTestCase 
{
    public function prepareTables()
    {
        $this->tables[] = "RelX";
        $this->tables[] = "RelY";
        parent::prepareTables();
    }

    public function prepareData() 
    {
	    Doctrine_Manager::getInstance()->getCurrentConnection()->beginTransaction();

        $cats = array('cat1', 'cat2');
	    $now = time();

        for ($i = 0; $i < 10; $i++) {
		    $age = $now - $i * 1000;
            $x = new RelX();
	        $x->name = "x $i";
		    $x->category = $cats[$i % 2];
		    $x->set('created_at', strftime("%Y-%m-%d %H:%M:%S", $age));
	        $x->save();
        
	        for ($j = 0; $j < 10; $j++) {
		        $y = new RelY();
		        $y->name = "y ".($i * 10 + $j);
		        $y->rel_x_id = $x->id;
		        $y->save();
		    }
	    }
        
        Doctrine_Manager::getInstance()->getCurrentConnection()->commit();
    }

    public function testSubqueryExtractionUsesWrongAliases()
    {
        $q = new Doctrine_Query();
        $q->from('RelX x');
    	$q->leftJoin('x.y xy');
    	$q->where('x.created_at IN (SELECT MAX(x2.created_at) latestInCategory FROM RelX x2 WHERE x.category = x2.category)');
    	$q->limit(5);

        //echo $sql = $q->getSqlQuery();
        //	echo $sql;

        $xs = $q->execute();

        // Doctrine_Ticket_1254_TestCase : method testSubqueryExtractionUsesWrongAliases failed on line 76 
        // This fails sometimes at
        $this->assertEqual(2, count($xs));
        
    }

}

class RelX extends Doctrine_Record {

  public function setTableDefinition() {
    $this->setTableName('rel_x');
    $this->hasColumn('name', 'string', 25, array());
    $this->hasColumn('category', 'string', 25, array());
    $this->hasColumn('created_at', 'timestamp', null, array());
  }

  public function setUp() {
    $this->HasMany('RelY as y', array('local' => 'id', 'foreign' => 'rel_x_id'));
  }

}

class RelY extends Doctrine_Record {

  public function setTableDefinition() {
    $this->setTableName('rel_y');
    $this->hasColumn('name', 'string', 25, array());
    $this->hasColumn('rel_x_id', 'integer', 10, array());
  }

  public function setUp() {

  }

}
