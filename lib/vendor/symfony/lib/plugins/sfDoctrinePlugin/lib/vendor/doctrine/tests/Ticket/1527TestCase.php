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
 * Doctrine_Ticket_1527_TestCase
 *
 * @package     Doctrine
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Ticket_1527_TestCase extends Doctrine_UnitTestCase 
{
    public function testTest()
    {
        $yml = <<<END
---
Ticket_1527_User:
  columns:
    username:
      type: string(255)
      extra:
        test: 123
    password:
      type: string(255)
END;

        $import = new Doctrine_Import_Schema();
        $schema = $import->buildSchema($yml, 'yml');
        $this->assertEqual($schema['Ticket_1527_User']['columns']['username']['extra']['test'], '123');

        $path = dirname(__FILE__) . '/../tmp';
        $import->importSchema($yml, 'yml', $path);
        
        require_once($path . '/generated/BaseTicket_1527_User.php');
        require_once($path . '/Ticket_1527_User.php');
        $username = Doctrine_Core::getTable('Ticket_1527_User')->getDefinitionOf('username');
        $this->assertEqual($username['extra']['test'], '123');
    }
}