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
 * Doctrine_Ticket_1449_TestCase
 *
 * @package     Doctrine
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Ticket_1449_TestCase extends Doctrine_UnitTestCase 
{
    public function prepareTables()
    {
        $this->tables[] = 'Ticket_1449_Document';
        $this->tables[] = 'Ticket_1449_Attachment';
        parent::prepareTables();
    }

    public function prepareData()
    {
        $document = new Ticket_1449_Document();
        $document->name = 'test';
        $document->Attachments[]->name = 'test 1';
        $document->Attachments[]->name = 'test 2';
        $document->Attachments[]->name = 'test 3';
        $document->save();
    }

    public function testTest()
    {
        $document = Doctrine_Query::create()
            ->select('d.id, d.name, a.id, a.document_id')
            ->from('Ticket_1449_Document d')
            ->leftJoin('d.Attachments a')
            ->limit(1)
            ->fetchOne();
        $this->assertEqual($document->state(), 4);
        foreach ($document->Attachments as $attachment)
        {
            $this->assertEqual($attachment->state(), 4);
        }
    }
}

class Ticket_1449_Document extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('name', 'string', 255);
        $this->hasColumn('test', 'string', 255);
    }

    public function setUp()
    {
        $this->hasMany('Ticket_1449_Attachment as Attachments', array('local'   => 'id',
                                                                      'foreign' => 'document_id'));
    }
}

class Ticket_1449_Attachment extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('document_id', 'integer');
        $this->hasColumn('name', 'string', 255);
    }

    public function setUp()
    {
        $this->hasOne('Ticket_1449_Document as Document', array('local'   => 'document_id',
                                                                'foreign' => 'id'));
    }
}