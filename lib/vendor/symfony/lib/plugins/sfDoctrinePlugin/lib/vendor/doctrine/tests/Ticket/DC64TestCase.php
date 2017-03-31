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
 * Doctrine_Ticket_DC64_TestCase
 *
 * @package     Doctrine
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Ticket_DC64_TestCase extends Doctrine_UnitTestCase 
{
    public function prepareTables()
    {
        $this->tables[] = 'Ticket_DC64_Article';
        parent::prepareTables();
    }

    public function testTicket()
    {
        $article = new Ticket_DC64_Article();
        $article->title = 'testing provider';
        $article->content = 'testing';
        $article->save();
        $this->assertEqual($article->slug, 'jwage_testing_provider');

        $article = new Ticket_DC64_Article();
        $article->title = 'testing provider';
        $article->content = 'testing';
        $article->save();
        $this->assertEqual($article->slug, 'jwage_testing_provider_1');

        $article = new Ticket_DC64_Article();
        $article->title = 'testing provider';
        $article->content = 'testing';
        $article->save();
        $this->assertEqual($article->slug, 'jwage_testing_provider_2');
    }
}

class Ticket_DC64_Article extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('title', 'string', 255);
        $this->hasColumn('content', 'clob');
    }

    public function setUp()
    {
        $this->actAs('Sluggable', array(
            'provider' => array($this, 'provideSlug'),
            'builder' => array($this, 'formatSlug')
        ));
    }

    public function __toString()
    {
        return $this->title;
    }

    public static function formatSlug($slug)
    {
        return str_replace('-', '_', Doctrine_Inflector::urlize($slug));
    }

    public static function provideSlug(Doctrine_Record $record)
    {
        return 'jwage ' . $record->title;
    }
}