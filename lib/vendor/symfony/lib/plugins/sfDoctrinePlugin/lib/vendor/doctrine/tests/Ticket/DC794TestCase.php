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
 * Doctrine_Ticket_DC794_TestCase
 *
 * @package     Doctrine
 * @author      Enrico Stahn <mail@enricostahn.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Ticket_DC794_TestCase extends Doctrine_UnitTestCase 
{
    public function prepareTables()
    {
        $this->tables[] = 'Ticket_DC794_Model';
        parent::prepareTables();
    }

    public function testTest()
    {
        $table = Doctrine_Core::getTable('Ticket_DC794_Model');
        
        $this->assertEqual($table->buildFindByWhere('IdOrigenOportunidadClienteOrId'), '(dctrn_find.idOrigenOportunidadCliente = ? OR dctrn_find.id = ?)');
        $this->assertEqual($table->buildFindByWhere('IdAndIdOrIdOrigenOportunidadCliente'), 'dctrn_find.id = ? AND (dctrn_find.id = ? OR dctrn_find.idOrigenOportunidadCliente = ?)');
        $this->assertEqual($table->buildFindByWhere('UsernameOrIdOrIdOrigenOportunidadCliente'), '(dctrn_find.Username = ? OR dctrn_find.id = ? OR dctrn_find.idOrigenOportunidadCliente = ?)');
    }
}

class Ticket_DC794_Model extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('id', 'integer', null, array(
            'type' => 'integer',
            'unsigned' => false,
            'primary' => true,
            'autoincrement' => true,
        ));
        $this->hasColumn('idOrigenOportunidadCliente', 'string', 255);
        $this->hasColumn('Username', 'string', 255);
        $this->hasColumn('password', 'string', 255);
    }
}