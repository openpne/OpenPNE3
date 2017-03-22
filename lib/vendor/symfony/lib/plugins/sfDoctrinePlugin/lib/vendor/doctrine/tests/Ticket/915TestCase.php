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
 * Doctrine_Ticket_915_TestCase
 *
 * @package     Doctrine
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Ticket_915_TestCase extends Doctrine_UnitTestCase
{
    public function prepareData() { }
    public function prepareTables()
    {
        $this->tables[] = 'Account';
        parent::prepareTables();    
    }

    public function testBug()
    {
        $yml = <<<END
---
Account:
  A1:
    Amount: 100
  A2:
    amount: 200
  A3:
    Amount: 300
  A4:
    Entity_id: -100
  A5:
    entity_id: -200
  A6:
    Entity_id: -300
END;

        file_put_contents('test.yml', $yml);
        $import = new Doctrine_Data_Import('test.yml');
        $import->setFormat("yml");

        // try to import garbled records (with incorrect field names,
        // e.g. "Amount" instead of "amount") and expect that doctrine
        // will raise an exception.
        try {
            $import->doImport();
            $this->fail('Doctrine did not raise any exceptions when were importing garbled records.');
        } catch (Exception $e) {
            $this->pass();
        }
    }
}