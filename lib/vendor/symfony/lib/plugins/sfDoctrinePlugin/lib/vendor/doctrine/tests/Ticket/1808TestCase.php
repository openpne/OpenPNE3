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
 * Doctrine_Ticket_1808_TestCase
 *
 * @package     Doctrine
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Ticket_1808_TestCase extends Doctrine_UnitTestCase 
{
    public function testTest()
    {
        $userTable = Doctrine_Core::getTable('User');

        $this->assertEqual($userTable->buildFindByWhere('NameAndPassword'), 'dctrn_find.name = ? AND dctrn_find.password = ?');
        $this->assertEqual($userTable->buildFindByWhere('NameOrLoginname'), '(dctrn_find.name = ? OR dctrn_find.loginname = ?)');
        $this->assertEqual($userTable->buildFindByWhere('NameAndPasswordOrLoginname'), 'dctrn_find.name = ? AND (dctrn_find.password = ? OR dctrn_find.loginname = ?)');
        $this->assertEqual($userTable->buildFindByWhere('NameAndPasswordOrLoginnameAndName'), 'dctrn_find.name = ? AND (dctrn_find.password = ? OR dctrn_find.loginname = ?) AND dctrn_find.name = ?');

        $user = new User();
        $user->name = 'bigtest';
        $user->loginname = 'cooltest';
        $user->Email->address = 'jonathan.wage@sensio.com';
        $user->save();

        $user2 = $userTable->findOneByNameAndLoginnameAndEmailId($user->name, $user->loginname, $user->email_id);
        $this->assertIdentical($user, $user2);

        $test = $userTable->findOneByNameAndLoginnameAndEmailId($user->name, $user->loginname, $user->email_id, Doctrine_Core::HYDRATE_ARRAY);
        $this->assertTrue(is_array($test));   
    }
}