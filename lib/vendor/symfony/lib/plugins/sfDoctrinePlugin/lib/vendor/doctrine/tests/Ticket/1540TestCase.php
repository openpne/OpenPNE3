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
 * Doctrine_Ticket_1540_TestCase
 *
 * @package     Doctrine
 * @author      Andrea Baron <andrea@bhweb.it>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0.2
 * @version     $Revision$
 */
class Doctrine_Ticket_1540_TestCase extends Doctrine_UnitTestCase 
{
    public function prepareTables()
    {
        $this->tables[] = 'Ticket_1540_TableName';
        parent::prepareTables();
    }
	
	public function testShouldNotConvertToAmpersandsInSelect()
    {
        $q = Doctrine_Query::create()
			->select('if(1 AND 2, 1, 2)')
            ->from('Ticket_1540_TableName t');
        $this->assertEqual($q->getSqlQuery(), 'SELECT if(1 AND 2, 1, 2) AS t__0 FROM ticket_1540__table_name t');
    }
	
    public function testShouldNotConvertToAmpersandsInWhere()
    {
        $q = Doctrine_Query::create()
            ->from('Ticket_1540_TableName t')
			->where('if(1 AND 2, 1, 2)', 1);
        $this->assertEqual($q->getSqlQuery(), 'SELECT t.id AS t__id FROM ticket_1540__table_name t WHERE (if(1 AND 2, 1, 2))');
    }
}

class Ticket_1540_TableName extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('id', 'integer', 4, array('primary' => true, 'autoincrement' => true));
    }

    public function setUp()
    {
        
    }
}