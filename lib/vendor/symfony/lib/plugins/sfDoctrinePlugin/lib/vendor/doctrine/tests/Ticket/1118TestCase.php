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
 * Doctrine_Ticket_1118_TestCase
 *
 * @package     Doctrine
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Ticket_1118_TestCase extends Doctrine_UnitTestCase 
{
    // Test that when a foreign key is detected that it sets the foreign key to the same type and length
    // of the related table primary key
    public function testTest()
    {
        $yml = <<<END
---
detect_relations: true
Ticket_1118_User:
  columns:
    username: string(255)
    password: string(255)
    ticket__1118__profile_id: string

Ticket_1118_Profile:
  columns:
    id:
      type: integer(4)
      autoincrement: true
      primary: true
    name: string(255)
END;
        try {
            file_put_contents('test.yml', $yml);

            $import = new Doctrine_Import_Schema();
            $array = $import->buildSchema('test.yml', 'yml');
            // Test that ticket__1118__profile_id is changed to to be integer(4) since the primary key of 
            // the relationship is set to that
            $this->assertEqual($array['Ticket_1118_User']['columns']['ticket__1118__profile_id']['type'], 'integer');
            $this->assertEqual($array['Ticket_1118_User']['columns']['ticket__1118__profile_id']['length'], '4');

            $this->pass();
        } catch (Exception $e) {
            $this->fail();
        }

        unlink('test.yml');
    }
}