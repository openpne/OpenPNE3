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
 * Doctrine_Ticket_1500_TestCase
 *
 * @package     Doctrine
 * @author      David Zülke <david.zuelke@bitextender.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    ?
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Ticket_1507_TestCase extends Doctrine_UnitTestCase 
{
	public function testInitiallyEmpty()
	{
		$c = new Doctrine_Ticket_1507_TestCase_TestConfigurable();
		
		$this->assertEqual(null, $c->getParam('foo'));
		$this->assertEqual(null, $c->getParam('foo', 'bar'));
		$this->assertEqual(null, $c->getParams());
		$this->assertEqual(null, $c->getParams('bar'));
		$this->assertEqual(array(), $c->getParamNamespaces());
	}
	
	public function testSetGetParamWithNamespace()
	{
		$c = new Doctrine_Ticket_1507_TestCase_TestConfigurable();
		
		$c->setParam('foo', 'bar', 'namespace');
		
		$this->assertEqual(array('foo' => 'bar'), $c->getParams('namespace'));
		$this->assertEqual('bar', $c->getParam('foo', 'namespace'));
		
		$this->assertEqual(array('namespace'), $c->getParamNamespaces());
	}
	
	public function testSetGetParamWithoutNamespace()
	{
		$c = new Doctrine_Ticket_1507_TestCase_TestConfigurable();
		
		$c->setParam('foo', 'bar');
		
		$this->assertEqual(array('foo' => 'bar'), $c->getParams());
		$this->assertEqual('bar', $c->getParam('foo'));
		
		$this->assertEqual(array($c->getAttribute(Doctrine_Core::ATTR_DEFAULT_PARAM_NAMESPACE)), $c->getParamNamespaces());
	}
	
	public function testSetGetParamWithNamespaceParent()
	{
		$p = new Doctrine_Ticket_1507_TestCase_TestConfigurable();
		$c = new Doctrine_Ticket_1507_TestCase_TestConfigurable();
		$c->setParent($p);
		
		$p->setParam('foo', 'bar', 'namespace');
		
		$this->assertEqual('bar', $c->getParam('foo', 'namespace'));
	}
	
	public function testSetGetParamWithoutNamespaceParent()
	{
		$p = new Doctrine_Ticket_1507_TestCase_TestConfigurable();
		$c = new Doctrine_Ticket_1507_TestCase_TestConfigurable();
		$c->setParent($p);
		
		$p->setParam('foo', 'bar');
		
		$this->assertEqual('bar', $c->getParam('foo'));
	}
}

class Doctrine_Ticket_1507_TestCase_TestConfigurable extends Doctrine_Configurable
{
}