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
 * <http://www.phpdoctrine.org>.
 */

/**
 * Doctrine_Template_TestCase
 *
 * @package     Doctrine
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.phpdoctrine.org
 * @since       1.2
 * @version     $Revision$
 */
class Doctrine_Ticket_DC399_TestCase extends Doctrine_UnitTestCase 
{
    public function prepareTables()
    {
      parent::prepareTables();
    }
    public function prepareData() 
    {
      $user = new User();
      $user->name = "sacho";
      $user->Phonenumber[0]->phonenumber = "1234567";
      $user->Phonenumber[1]->phonenumber = "123123123";
      $user->Phonenumber[2]->phonenumber = "345345345";

      $user->save();
    }

    public function testUnlinkCausesDeleteAfterSave()
    {
      $user = Doctrine_Core::getTable("User")->findOneByName("sacho");
      $user->refreshRelated("Phonenumber");
      
      //Unlinking(even with now=true) removes the connection between User and Phonenumber, but does not delete the phone number
      //Only updates phonenumber's user_id to null
      //However, if we unlink and then save the $user, the phonenumber is deleted from the database

      $phone_id = $user->Phonenumber[0]->id;
      $user->unlink("Phonenumber", $user->Phonenumber[0]->id);
      $user->save();

      $phone = Doctrine_Core::getTable("Phonenumber")->find($phone_id);
      $this->assertEqual($phone_id, $phone->id);
    }

}