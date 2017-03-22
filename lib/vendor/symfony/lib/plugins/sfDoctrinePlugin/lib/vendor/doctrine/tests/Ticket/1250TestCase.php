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
 * Doctrine_Ticket_1015_TestCase
 *
 * @package     Doctrine
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Ticket_1250_TestCase extends Doctrine_UnitTestCase {
  public function prepareTables() {
    $this->tables[] = 'Doctrine_Ticket_1250_i18n';
    parent::prepareTables();
  }
  
  public function testTicket()
  {
     try {
        $r = new Doctrine_Ticket_1250_i18n();
        // This is needed since all fields are internationalized.
        // Reason for not fixing that is BC. Manual describes this behavior very well
        $r->state('TDIRTY');
        $r->Translation['en']->title = 'Title in english';
        $r->Translation['en']->content = 'Content in english';
        $r->Translation['fr']->title = 'Titre en français';
        $r->Translation['en']->content = 'Contenu en français';
        $r->save();
      } catch (Exception $e) {
          $this->fail($e->getMessage());
      }
      
      $this->assertEqual(1, $r->id);
  }
}

class Doctrine_Ticket_1250_i18n extends Doctrine_Record
{
  public function setTableDefinition()
  {
    $this->setTableName('Doctrine_Ticket_1250_i18n');
    $this->hasColumn('title', 'string', 255);
    $this->hasColumn('content', 'string', null);
  }

  public function setUp()
  {
    parent::setUp();
    $i18n0 = new Doctrine_Template_I18n(array('length' => 5, 'fields' => array(0 => 'title', 1 => 'content')));
    $this->actAs($i18n0);
  }
}