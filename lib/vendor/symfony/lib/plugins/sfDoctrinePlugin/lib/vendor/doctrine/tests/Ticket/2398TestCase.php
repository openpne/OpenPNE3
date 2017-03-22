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
 * @author      Russ Flynn <russ@eatmymonkeydust.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Ticket_2398_TestCase extends Doctrine_UnitTestCase 
{
    // Since this file is the subject of the test, we need to add some utf-8 chars to mess up
    // the non-binary-safe count.
    private $randomUtf8 = "øåæØÅÆØÅæøåøæØÅæøåØÆØåøÆØÅøæøåøæøåÅØÆØ";
  
    public function testIsValidLength()
    {
        $binaryValue = fread(fopen(__FILE__, 'r'), filesize(__FILE__));

        //Should pass with size the same size as maximum size
        $this->assertTrue(Doctrine_Validator::validateLength($binaryValue, "blob", filesize(__FILE__)));
        
        //Should fail with maximum size 1 less than actual file size 
        $this->assertFalse(Doctrine_Validator::validateLength($binaryValue, "blob", filesize(__FILE__) -1));
    }
}
