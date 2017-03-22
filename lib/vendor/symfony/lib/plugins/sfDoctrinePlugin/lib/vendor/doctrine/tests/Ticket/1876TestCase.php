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
 * Doctrine_Ticket_1821_TestCase
 *
 * @package     Doctrine
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 * @author      Andrea Baron <andrea@bhweb.it>
 */
class Doctrine_Ticket_1876_TestCase extends Doctrine_UnitTestCase
{
    public function prepareTables()
    {
        $this->tables = array(
            'T1876_Recipe',
        	'T1876_Company',
        	'T1876_RecipeIngredient',
        );
        parent::prepareTables();
    }
    
    public function prepareData()
    {
        for ($i = 0; $i < 2; $i++) {
            $company = new T1876_Company();
            $company->name = 'Test Company ' . ($i + 1);
            $company->save();
        }
        
        for ($i = 0; $i < 10; $i++) {
            $recipe = new T1876_Recipe();
            
            $recipe->name = 'test ' . $i;
            $recipe->company_id = ($i % 3 == 0) ? 1 : 2;
            $recipe->RecipeIngredients[]->name = 'test';
            
            $recipe->save();
            
            if ($i % 2 == 0) {
                $recipe->delete(); 
            }
        }
    }
    
    public function testTicket()
    {
        Doctrine_Manager::getInstance()->setAttribute(Doctrine_Core::ATTR_USE_DQL_CALLBACKS, true);
        
        try {
            $q = Doctrine_Query::create()
                ->from('T1876_Recipe r')
                ->leftJoin('r.Company c')
                ->leftJoin('r.RecipeIngredients')
                ->addWhere('c.id = ?', 2);
            
            $this->assertEqual(
                $q->getCountSqlQuery(), 
                'SELECT COUNT(*) AS num_results ' . 
                'FROM (SELECT t.id FROM t1876__recipe t ' . 
                'LEFT JOIN t1876__company t2 ON t.company_id = t2.id AND (t2.deleted_at IS NULL) ' .
                'LEFT JOIN t1876__recipe_ingredient t3 ON t.id = t3.recipe_id AND (t3.deleted_at IS NULL) ' .
                'WHERE t2.id = ? AND (t.deleted_at IS NULL) ' .
                'GROUP BY t.id) dctrn_count_query'
            );
            $this->assertEqual($q->count(), 3);
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        }
        
        Doctrine_Manager::getInstance()->setAttribute(Doctrine_Core::ATTR_USE_DQL_CALLBACKS, false);
    }
}
        
class T1876_Recipe extends Doctrine_Record {
    public function setTableDefinition() {
        $this->hasColumn('id', 'integer', null, array('autoincrement' => true, 'primary' => true));
        $this->hasColumn('company_id', 'integer', null);
        $this->hasColumn('name', 'string', 255);
    }
    
    public function setUp() {
        $this->hasOne('T1876_Company as Company', array('local' => 'company_id', 'foreign' => 'id'));
        $this->hasMany('T1876_RecipeIngredient as RecipeIngredients', array('local' => 'id', 'foreign' => 'recipe_id'));
        
        $this->actAs('SoftDelete');
    }
}

class T1876_Company extends Doctrine_Record {
    public function setTableDefinition() {
        $this->hasColumn('id', 'integer', null, array('autoincrement' => true, 'primary' => true));
        $this->hasColumn('name', 'string', 255);
    }
    
    public function setUp() {
        $this->hasMany('T1876_Recipe as Recipes', array('local' => 'id', 'foreign' => 'company_id'));
        
        $this->actAs('SoftDelete');
    }
}

class T1876_RecipeIngredient extends Doctrine_Record {
    public function setTableDefinition() {
        $this->hasColumn('id', 'integer', null, array('autoincrement' => true, 'primary' => true));
        $this->hasColumn('recipe_id', 'integer', null);
        $this->hasColumn('name', 'string', 255);
    }
    
    public function setUp() {
        $this->hasOne('T1876_Recipe as Recipe', array('local' => 'recipe_id', 'foreign' => 'id'));
        
        $this->actAs('SoftDelete');
    }
}