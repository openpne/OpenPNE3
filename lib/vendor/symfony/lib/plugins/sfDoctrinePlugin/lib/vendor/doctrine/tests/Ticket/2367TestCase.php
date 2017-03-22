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
 * Doctrine_Ticket_2367_TestCase
 *
 * @package     Doctrine
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Ticket_2367_TestCase extends Doctrine_UnitTestCase 
{
    public function prepareTables()
    {
        $this->tables[] = 'Ticket_2367_Article';
        parent::prepareTables();
    }

    public function testTest()
    {
        $article = new Ticket_2367_Article();
        $article->fromArray(array(
          'Translation' => array(
              'en' => array(
                'content' => 'article content',
              ),
              'fr' => array(
                'content' => 'contenu de l\'article'))));
        $article->save();
        $article->delete();
        $check = (bool) Doctrine_Core::getTable('Ticket_2367_ArticleTranslation')->count();
        $this->assertFalse($check);
    }
}

class Ticket_2367_Article extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('id', 'integer', 2, array('type' => 'integer', 'primary' => true,
          'autoincrement' => true, 'unsigned' => true, 'length' => '2'));
        $this->hasColumn('content', 'string', 100, array('type' => 'string', 'length' => '100'));

        $this->option('type', 'MyISAM');
    }

    public function setUp()
    {
      $i18n0 = new Doctrine_Template_I18n(array(
        'appLevelDelete' => true,
        'fields' => array(
          0 => 'content')));
      $this->actAs($i18n0);
    }
}