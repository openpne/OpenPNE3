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
 * Doctrine_Connection_Custom_TestCase
 *
 * @package     Doctrine
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Connection_Custom_TestCase extends Doctrine_UnitTestCase 
{
    public function setUp()
    {
        $manager = Doctrine_Manager::getInstance();
        $manager->registerConnectionDriver('test', 'Doctrine_Connection_Test');
        $this->_conn = $manager->openConnection('test://username:password@localhost/dbname', false);
        $this->_dbh = $this->_conn->getDbh();
    }

    public function testConnection()
    {
        $this->assertTrue($this->_conn instanceof Doctrine_Connection_Test);
        $this->assertTrue($this->_dbh instanceof Doctrine_Adapter_Test);
    }
}

class Doctrine_Connection_Test extends Doctrine_Connection_Common
{
    
}

class Doctrine_Adapter_Test implements Doctrine_Adapter_Interface
{
    public function __construct($dsn, $username, $password, $options)
    {
    }

    public function prepare($prepareString)
    {
    }

    public function query($queryString)
    {
    }

    public function quote($input)
    {
    }

    public function exec($statement)
    {
    }

    public function lastInsertId()
    {
    }

    public function beginTransaction()
    {
    }

    public function commit()
    {
    }

    public function rollBack()
    {
    }

    public function errorCode()
    {
    }

    public function errorInfo()
    {
    }

    public function getAttribute($attribute)
    {
    }

    public function setAttribute($attribute, $value)
    {
    }

    public function sqliteCreateFunction()
    {
    }
}