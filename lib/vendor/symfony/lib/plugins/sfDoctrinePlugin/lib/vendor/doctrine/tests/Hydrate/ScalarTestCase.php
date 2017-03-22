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
 * Doctrine_Hydrate_Scalar_TestCase
 *
 * @package     Doctrine
 * @author      Roman Borschel <roman@code-factory.org>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.1
 * @version     $Revision$
 */
class Doctrine_Hydrate_Scalar_TestCase extends Doctrine_UnitTestCase
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
    
    public function testHydrateScalarWithJoin()
    {
        $q = Doctrine_Query::create();
        $q->select("u.*, p.*")
            ->from("User u")
            ->innerJoin("u.Phonenumber p");
        
        $res = $q->execute(array(), Doctrine_Core::HYDRATE_SCALAR);
        
        $this->assertTrue(is_array($res));
        $this->assertEqual(2, count($res));
        //row1
        $this->assertEqual(1, $res[0]['u_id']);
        $this->assertEqual('romanb', $res[0]['u_name']);
        $this->assertEqual(null, $res[0]['u_loginname']);
        $this->assertEqual(null, $res[0]['u_password']);
        $this->assertEqual(0, $res[0]['u_type']);
        $this->assertEqual(null, $res[0]['u_created']);
        $this->assertEqual(null, $res[0]['u_updated']);
        $this->assertEqual(null, $res[0]['u_email_id']);
        $this->assertEqual(1, $res[0]['p_id']);
        $this->assertEqual(112, $res[0]['p_phonenumber']);
        $this->assertEqual(1, $res[0]['p_entity_id']);
        //row2
        $this->assertEqual(1, $res[1]['u_id']);
        $this->assertEqual('romanb', $res[1]['u_name']);
        $this->assertEqual(null, $res[1]['u_loginname']);
        $this->assertEqual(null, $res[1]['u_password']);
        $this->assertEqual(0, $res[1]['u_type']);
        $this->assertEqual(null, $res[1]['u_created']);
        $this->assertEqual(null, $res[1]['u_updated']);
        $this->assertEqual(null, $res[1]['u_email_id']);
        $this->assertEqual(2, $res[1]['p_id']);
        $this->assertEqual(110, $res[1]['p_phonenumber']);
        $this->assertEqual(1, $res[1]['p_entity_id']);
        
        $q->free();
    }
    
    public function testHydrateScalar()
    {
        $q = Doctrine_Query::create();
        $q->select("u.*")->from("User u");
        
        $res = $q->execute(array(), Doctrine_Core::HYDRATE_SCALAR);
        
        $this->assertTrue(is_array($res));
        $this->assertEqual(1, count($res));
        //row1
        $this->assertEqual(1, $res[0]['u_id']);
        $this->assertEqual('romanb', $res[0]['u_name']);
        $this->assertEqual(null, $res[0]['u_loginname']);
        $this->assertEqual(null, $res[0]['u_password']);
        $this->assertEqual(0, $res[0]['u_type']);
        $this->assertEqual(null, $res[0]['u_created']);
        $this->assertEqual(null, $res[0]['u_updated']);
        $this->assertEqual(null, $res[0]['u_email_id']);
        
        $q->free();
    }
    
    public function testHydrateSingleScalarDoesNotAddPKToSelect()
    {
        $q = Doctrine_Query::create();
        $q->select("u.name")->from("User u");
        $res = $q->execute(array(), Doctrine_Core::HYDRATE_SINGLE_SCALAR);
        $this->assertEqual('romanb', $res);
        $q->free();
    }
    
    public function testHydrateSingleScalarWithAggregate()
    {
        $q = Doctrine_Query::create();
        $q->select("COUNT(u.id) num_ids")->from("User u");
        $res = $q->execute(array(), Doctrine_Core::HYDRATE_SINGLE_SCALAR);
        $this->assertEqual(1, $res);
        $q->free();
    }
    
    public function testHydrateScalarWithJoinAndAggregate()
    {
        $q = Doctrine_Query::create();
        $q->select("u.id, UPPER(u.name) nameUpper, p.*")
            ->from("User u")
            ->innerJoin("u.Phonenumber p");
        
        $res = $q->execute(array(), Doctrine_Core::HYDRATE_SCALAR);
        
        $this->assertTrue(is_array($res));
        $this->assertEqual(2, count($res));
        
        //row1
        $this->assertEqual(1, $res[0]['u_id']);
        $this->assertEqual('ROMANB', $res[0]['u_nameUpper']);
        $this->assertEqual(1, $res[0]['p_id']);
        $this->assertEqual(112, $res[0]['p_phonenumber']);
        $this->assertEqual(1, $res[0]['p_entity_id']);
        //row2
        $this->assertEqual(1, $res[1]['u_id']);
        $this->assertEqual('ROMANB', $res[1]['u_nameUpper']);
        $this->assertEqual(2, $res[1]['p_id']);
        $this->assertEqual(110, $res[1]['p_phonenumber']);
        $this->assertEqual(1, $res[1]['p_entity_id']);
        
        $q->free();
    }

    public function testHydrateArrayShallowWithJoin()
    {
        $q = Doctrine_Query::create();
        $q->select("u.*, p.id as phonenumber_id, p.phonenumber, p.entity_id")
            ->from("User u")
            ->innerJoin("u.Phonenumber p");

        $res = $q->execute(array(), Doctrine_Core::HYDRATE_ARRAY_SHALLOW);

        $this->assertTrue(is_array($res));
        $this->assertEqual(2, count($res));
        //row1
        $this->assertEqual(1, $res[0]['id']);
        $this->assertEqual('romanb', $res[0]['name']);
        $this->assertEqual(null, $res[0]['loginname']);
        $this->assertEqual(null, $res[0]['password']);
        $this->assertEqual(0, $res[0]['type']);
        $this->assertEqual(null, $res[0]['created']);
        $this->assertEqual(null, $res[0]['updated']);
        $this->assertEqual(null, $res[0]['email_id']);
        $this->assertEqual(1, $res[0]['phonenumber_id']);
        $this->assertEqual(112, $res[0]['phonenumber']);
        $this->assertEqual(1, $res[0]['entity_id']);
        //row2
        $this->assertEqual(1, $res[1]['id']);
        $this->assertEqual('romanb', $res[1]['name']);
        $this->assertEqual(null, $res[1]['loginname']);
        $this->assertEqual(null, $res[1]['password']);
        $this->assertEqual(0, $res[1]['type']);
        $this->assertEqual(null, $res[1]['created']);
        $this->assertEqual(null, $res[1]['updated']);
        $this->assertEqual(null, $res[1]['email_id']);
        $this->assertEqual(2, $res[1]['phonenumber_id']);
        $this->assertEqual(110, $res[1]['phonenumber']);
        $this->assertEqual(1, $res[1]['entity_id']);

        $q->free();
    }

    public function testHydrateArrayShallow()
    {
        $q = Doctrine_Query::create();
        $q->select("u.*")->from("User u");

        $res = $q->execute(array(), Doctrine_Core::HYDRATE_ARRAY_SHALLOW);

        $this->assertTrue(is_array($res));
        $this->assertEqual(1, count($res));
        //row1
        $this->assertEqual(1, $res[0]['id']);
        $this->assertEqual('romanb', $res[0]['name']);
        $this->assertEqual(null, $res[0]['loginname']);
        $this->assertEqual(null, $res[0]['password']);
        $this->assertEqual(0, $res[0]['type']);
        $this->assertEqual(null, $res[0]['created']);
        $this->assertEqual(null, $res[0]['updated']);
        $this->assertEqual(null, $res[0]['email_id']);

        $q->free();
    }

    public function testHydrateArrayShallowWithJoinAndAggregate()
    {
        $q = Doctrine_Query::create();
        $q->select("u.id, UPPER(u.name) nameUpper, p.id as phonenumber_id, p.phonenumber, p.entity_id")
            ->from("User u")
            ->innerJoin("u.Phonenumber p");

        $res = $q->execute(array(), Doctrine_Core::HYDRATE_ARRAY_SHALLOW);

        $this->assertTrue(is_array($res));
        $this->assertEqual(2, count($res));

        //row1
        $this->assertEqual(1, $res[0]['id']);
        $this->assertEqual('ROMANB', $res[0]['nameUpper']);
        $this->assertEqual(1, $res[0]['id']);
        $this->assertEqual(112, $res[0]['phonenumber']);
        $this->assertEqual(1, $res[0]['entity_id']);
        //row2
        $this->assertEqual(1, $res[1]['id']);
        $this->assertEqual('ROMANB', $res[1]['nameUpper']);
        $this->assertEqual(2, $res[1]['phonenumber_id']);
        $this->assertEqual(110, $res[1]['phonenumber']);
        $this->assertEqual(1, $res[1]['entity_id']);

        $q->free();
    }
}