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
 * Doctrine_Hydrate_CollectionInitialization_TestCase
 *
 * @package     Doctrine
 * @author      Roman Borschel <roman@code-factory.org>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Hydrate_CollectionInitialization_TestCase extends Doctrine_UnitTestCase
{
    public function prepareData()
    {
        $user = new User();
        $user->name = 'romanb';
        
        $user->Phonenumber[0]->phonenumber = '112';
        $user->Phonenumber[1]->phonenumber = '110';
        
        $user->save();
    }
    
    public function prepareTables()
    {
        $this->tables = array('Entity', 'Phonenumber'); 
        parent::prepareTables();
    }
    
    public function testCollectionsAreReinitializedOnHydration()
    {
        // query for user with first phonenumber.
        $q = Doctrine_Query::create();
        $q->select("u.*, p.*")->from("User u")->innerJoin("u.Phonenumber p")
                ->where("p.phonenumber = '112'");
        
        $users = $q->execute();
        $this->assertEqual(1, count($users));
        $this->assertEqual(1, count($users[0]->Phonenumber));
        $this->assertEqual('112', $users[0]->Phonenumber[0]->phonenumber);
        
        // now query again. this time for the other phonenumber. collection should be re-initialized.
        $q = Doctrine_Query::create();
        $q->select("u.*, p.*")->from("User u")->innerJoin("u.Phonenumber p")
                ->where("p.phonenumber = '110'");
        $users = $q->execute();
        $this->assertEqual(1, count($users));
        $this->assertEqual(1, count($users[0]->Phonenumber));
        $this->assertEqual('110', $users[0]->Phonenumber[0]->phonenumber);

        // now query again. this time for both phonenumbers. collection should be re-initialized.
        $q = Doctrine_Query::create();
        $q->select("u.*, p.*")->from("User u")->innerJoin("u.Phonenumber p");
        $users = $q->execute();
        $this->assertEqual(1, count($users));
        $this->assertEqual(2, count($users[0]->Phonenumber));
        $this->assertEqual('112', $users[0]->Phonenumber[0]->phonenumber);
        $this->assertEqual('110', $users[0]->Phonenumber[1]->phonenumber);

        // now query AGAIN for both phonenumbers. collection should be re-initialized (2 elements, not 4).
        $q = Doctrine_Query::create();
        $q->select("u.*, p.*")->from("User u")->innerJoin("u.Phonenumber p");
        $users = $q->execute();
        $this->assertEqual(1, count($users));
        $this->assertEqual(2, count($users[0]->Phonenumber));
        $this->assertEqual('112', $users[0]->Phonenumber[0]->phonenumber);
        $this->assertEqual('110', $users[0]->Phonenumber[1]->phonenumber);
    }
}
