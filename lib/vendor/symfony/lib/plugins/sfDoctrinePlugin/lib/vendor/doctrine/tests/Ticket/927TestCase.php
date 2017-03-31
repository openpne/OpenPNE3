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
 * Doctrine_Ticket_927_TestCase
 *
 * @package     Doctrine
 * @author      David Stendardi <david.stendardi@adenclassifieds.com>
 * @category    Query
 * @link        www.doctrine-project.org
 * @since       0.10.4
 * @version     $Revision$
 */
class Doctrine_Ticket_927_TestCase extends Doctrine_UnitTestCase
{
    public function prepareData()
    {
        $oEmail = new Email;
        $oEmail->address = 'david.stendardi@adenclassifieds.com';
        $oEmail->save();
    }

    public function prepareTables()
    {
        $this->tables = array();
        $this->tables[] = 'Email';

        parent :: prepareTables();
    }

    public function testTicket()
    {
      $q = new Doctrine_Query();

      try {
          // simple query with deep relations
          $q->update('Email')
              ->set('address', '?', 'new@doctrine.org')
              ->where('address = ?', 'david.stendardi@adenclassifieds.com')
              ->execute();
      } catch (Exception $e) {
        $this->fail('Query :: set do not support values containing dot. Exception: ' . $e->getMessage());
      }
    }
}