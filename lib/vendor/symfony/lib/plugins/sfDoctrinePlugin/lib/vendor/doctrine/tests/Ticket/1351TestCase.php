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
 * Doctrine_Ticket_1351_TestCase
 *
 * @package     Doctrine
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Ticket_1351_TestCase extends Doctrine_UnitTestCase 
{
    public function prepareTables()
    {
        $this->tables[] = 'Ticket_1351_Article';
        parent::prepareTables();
    }

    public function testTest()
    {
        $yml = <<<END
Ticket_1351_Article:
  Ticket_1351_Article_1:
    Translation:
      en:
        title: Test title english
        body: Test body english
      fr:
        title: Test title french
        body: Test body french
END;
        file_put_contents('test.yml', $yml);
        Doctrine_Core::loadData('test.yml', true);
        unlink('test.yml');
        $results = Doctrine_Query::create()
                    ->from('Ticket_1351_Article a, a.Translation t')
                    ->fetchArray();
        $this->assertEqual($results[0]['Translation']['en']['title'], 'Test title english');
    }
}

class Ticket_1351_Article extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('title', 'string', 255);
        $this->hasColumn('body', 'clob');
    }

    public function setUp()
    {
        $i18n0 = new Doctrine_Template_I18n(array('fields' => array(0 => 'title', 1 => 'body')));
        $searchable1 = new Doctrine_Template_Searchable(array('fields' => array(0 => 'title')));
        $i18n0->addChild($searchable1);
        $this->actAs($i18n0);
    }
}