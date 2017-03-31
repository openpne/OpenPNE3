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
 * Doctrine_Ticket_1206_TestCase
 *
 * @package     Doctrine
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Ticket_1206_TestCase extends Doctrine_UnitTestCase 
{
    public function prepareTables()
    {
        Doctrine_Manager::connection()->setAttribute(Doctrine_Core::ATTR_TBLNAME_FORMAT, 'prefix_%s');
        $this->tables[] = 'Ticket_1206_BlogPost';
        parent::prepareTables();
    }

    public function testTest()
    {
        $this->assertEqual(Doctrine_Core::getTable('Ticket_1206_BlogPost')->getTableName(), 'prefix_ticket_1206__blog_post');
        $this->assertEqual(Doctrine_Core::getTable('Ticket_1206_BlogPostTranslation')->getTableName(), 'prefix_ticket_1206__blog_post_translation');
        Doctrine_Manager::connection()->setAttribute(Doctrine_Core::ATTR_TBLNAME_FORMAT, '%s');
    }
}

class Ticket_1206_BlogPost extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('name', 'string', 255);
        $this->hasColumn('title', 'string', 255);
        $this->hasColumn('body', 'clob');
    }

    public function setUp()
    {
        $this->actAs('I18n', array('fields' => array('title', 'body')));
    }
}