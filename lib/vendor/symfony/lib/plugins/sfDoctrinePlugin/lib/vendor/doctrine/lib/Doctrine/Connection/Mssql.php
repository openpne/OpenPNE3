<?php
/*
 *  $Id: Mssql.php 7690 2010-08-31 17:11:24Z jwage $
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
 * Doctrine_Connection_Mssql
 *
 * @package     Doctrine
 * @subpackage  Connection
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * @author      Lukas Smith <smith@pooteeweet.org> (PEAR MDB2 library)
 * @version     $Revision: 7690 $
 * @link        www.doctrine-project.org
 * @since       1.0
 */
class Doctrine_Connection_Mssql extends Doctrine_Connection_Common
{
    /**
     * @var string $driverName                  the name of this connection driver
     */
    protected $driverName = 'Mssql';

    /**
     * the constructor
     *
     * @param Doctrine_Manager $manager
     * @param PDO $pdo                          database handle
     */
    public function __construct(Doctrine_Manager $manager, $adapter)
    {
        // initialize all driver options
        $this->supported = array(
                          'sequences'             => 'emulated',
                          'indexes'               => true,
                          'affected_rows'         => true,
                          'transactions'          => true,
                          'summary_functions'     => true,
                          'order_by_text'         => true,
                          'current_id'            => 'emulated',
                          'limit_queries'         => 'emulated',
                          'LOBs'                  => true,
                          'replace'               => 'emulated',
                          'sub_selects'           => true,
                          'auto_increment'        => true,
                          'primary_key'           => true,
                          'result_introspection'  => true,
                          'prepared_statements'   => 'emulated',
                          );

        $this->properties['varchar_max_length'] = 8000;

        parent::__construct($manager, $adapter);
    }

    /**
     * quoteIdentifier
     * Quote a string so it can be safely used as a table / column name
     *
     * Quoting style depends on which database driver is being used.
     *
     * @param string $identifier    identifier name to be quoted
     * @param bool   $checkOption   check the 'quote_identifier' option
     *
     * @return string  quoted identifier string
     */
    public function quoteIdentifier($identifier, $checkOption = false)
    {
        if ($checkOption && ! $this->getAttribute(Doctrine_Core::ATTR_QUOTE_IDENTIFIER)) {
            return $identifier;
        }

        if (strpos($identifier, '.') !== false) {
            $parts = explode('.', $identifier);
            $quotedParts = array();
            foreach ($parts as $p) {
                $quotedParts[] = $this->quoteIdentifier($p);
            }

            return implode('.', $quotedParts);
        }

        return '[' . trim($identifier, '[]') . ']';
    }

    /**
     * Adds an adapter-specific LIMIT clause to the SELECT statement.
     * Inspired by Doctrine2 DBAL
     *
     * @param string $query
     * @param mixed $limit
     * @param mixed $offset
     * @param boolean $isSubQuery
     * @param Doctrine_Query $queryOrigin
     * @link https://github.com/doctrine/dbal/blob/master/lib/Doctrine/DBAL/Platforms/MsSqlPlatform.php#L607
     * @link http://www.toosweettobesour.com/2010/09/16/doctrine-1-2-mssql-alternative-limitpaging/
     * @return string
     */
    public function modifyLimitQuery($query, $limit = false, $offset = false, $isManip = false, $isSubQuery = false, Doctrine_Query $queryOrigin = null)
    {
        if ($limit === false || !($limit > 0)) {
            return $query;
        }

        $orderby = stristr($query, 'ORDER BY');

        if ($offset !== false && $orderby === false) {
            throw new Doctrine_Connection_Exception("OFFSET cannot be used in MSSQL without ORDER BY due to emulation reasons.");
        }

        $limit = intval($limit);
        $offset = intval($offset);

        if ($offset < 0) {
            throw new Doctrine_Connection_Exception("LIMIT argument offset=$offset is not valid");
        }

        if ($offset == 0) {
           $query = preg_replace('/^SELECT( DISTINCT)?\s/i', 'SELECT\1 TOP ' . $limit . ' ', $query);
        } else {
            $over = stristr($query, 'ORDER BY');

            if (!$over) {
                $over = 'ORDER BY (SELECT 0)';
            } else {
                // Remove ORDER BY clause from $query
                $query = stristr($query, 'ORDER BY', true);
            }

            // Remove the first SELECT from query
            $query = substr($query, strlen('SELECT '));
            $select = 'SELECT';

            if (0 === strpos($query, 'DISTINCT'))
            {
              $query = substr($query, strlen('DISTINCT '));
              $select .= ' DISTINCT';
            }

            $start = $offset + 1;
            $end = $offset + $limit;

            $query = "SELECT * FROM ($select ROW_NUMBER() OVER ($over) AS [DOCTRINE_ROWNUM], $query) AS [doctrine_tbl] WHERE [DOCTRINE_ROWNUM] BETWEEN $start AND $end";
        }

        return $query;
    }


    /**
     * Parse an OrderBy-Statement into chunks
     *
     * @param string $orderby
     */
    private function parseOrderBy($orderby)
    {
        $matches = array();
        $chunks  = array();
        $tokens  = array();
        $parsed  = str_ireplace('ORDER BY', '', $orderby);

        preg_match_all('/(\w+\(.+?\)\s+(ASC|DESC)),?/', $orderby, $matches);

        $matchesWithExpressions = $matches[1];

        foreach ($matchesWithExpressions as $match) {
            $chunks[] = $match;
            $parsed = str_replace($match, '##' . (count($chunks) - 1) . '##', $parsed);
        }

        $tokens = preg_split('/,/', $parsed);

        for ($i = 0, $iMax = count($tokens); $i < $iMax; $i++) {
            $tokens[$i] = trim(preg_replace_callback('/##(\d+)##/', function($m) { return $chunks[$m[1]]; }, $tokens[$i]));
        }

        return $tokens;
    }

    /**
     * Order and Group By are not possible on columns from type text.
     * This method fix this issue by wrap the given term (column) into a CAST directive.
     *
     * @see DC-828
     * @param Doctrine_Table $table
     * @param string $field
     * @param string $term The term which will changed if it's necessary, depending to the field type.
     * @return string
     */
    public function modifyOrderByColumn(Doctrine_Table $table, $field, $term)
    {
        $def = $table->getDefinitionOf($field);

        if ($def['type'] == 'string' && $def['length'] === NULL) {
            $term = 'CAST(' . $term . ' AS varchar(8000))';
        }

        return $term;
    }

    /**
     * Creates dbms specific LIMIT/OFFSET SQL for the subqueries that are used in the
     * context of the limit-subquery algorithm.
     *
     * @return string
     */
    public function modifyLimitSubquery(Doctrine_Table $rootTable, $query, $limit = false, $offset = false, $isManip = false)
    {
        return $this->modifyLimitQuery($query, $limit, $offset, $isManip, true);
    }

    /**
     * return version information about the server
     *
     * @param bool   $native  determines if the raw version string should be returned
     * @return array    version information
     */
    public function getServerVersion($native = false)
    {
        if ($this->serverInfo) {
            $serverInfo = $this->serverInfo;
        } else {
            $query      = 'SELECT @@VERSION';
            $serverInfo = $this->fetchOne($query);
        }
        // cache server_info
        $this->serverInfo = $serverInfo;
        if ( ! $native) {
            if (preg_match('/([0-9]+)\.([0-9]+)\.([0-9]+)/', $serverInfo, $tmp)) {
                $serverInfo = array(
                    'major' => $tmp[1],
                    'minor' => $tmp[2],
                    'patch' => $tmp[3],
                    'extra' => null,
                    'native' => $serverInfo,
                );
            } else {
                $serverInfo = array(
                    'major' => null,
                    'minor' => null,
                    'patch' => null,
                    'extra' => null,
                    'native' => $serverInfo,
                );
            }
        }
        return $serverInfo;
    }

    /**
     * Checks if there's a sequence that exists.
     *
     * @param  string $seq_name     The sequence name to verify.
     * @return boolean              The value if the table exists or not
     */
    public function checkSequence($seqName)
    {
        $query = 'SELECT * FROM ' . $seqName;
        try {
            $this->exec($query);
        } catch(Doctrine_Connection_Exception $e) {
            if ($e->getPortableCode() == Doctrine_Core::ERR_NOSUCHTABLE) {
                return false;
            }

            throw $e;
        }
        return true;
    }

    /**
     * execute
     * @param string $query     sql query
     * @param array $params     query parameters
     *
     * @return PDOStatement|Doctrine_Adapter_Statement
     */
    public function execute($query, array $params = array())
    {
        if(! empty($params)) {
            $query = $this->replaceBoundParamsWithInlineValuesInQuery($query, $params);
        }

        return parent::execute($query, array());
    }

    /**
     * execute
     * @param string $query     sql query
     * @param array $params     query parameters
     *
     * @return PDOStatement|Doctrine_Adapter_Statement
     */
    public function exec($query, array $params = array())
    {
        if(! empty($params)) {
            $query = $this->replaceBoundParamsWithInlineValuesInQuery($query, $params);
        }

        return parent::exec($query, array());
    }

    /**
     * Replaces bound parameters and their placeholders with explicit values.
     *
     * Workaround for http://bugs.php.net/36561
     *
     * @param string $query
     * @param array $params
     */
    protected function replaceBoundParamsWithInlineValuesInQuery($query, array $params)
    {
        foreach($params as $key => $value) {
            $re = '/(?<=WHERE|VALUES|SET|JOIN)(.*?)(\?)/';
            $query = preg_replace($re, "\\1##{$key}##", $query, 1);
        }

        $self = $this;
        $query = preg_replace_callback('/##(\d+)##/', function($m) use ($params, $self) {
            return (null === $params[$m[1]]) ? 'NULL' : $self->quote($params[$m[1]]);
        }, $query);

        return $query;
    }

    /**
     * Inserts a table row with specified data.
     *
     * @param Doctrine_Table $table     The table to insert data into.
     * @param array $values             An associative array containing column-value pairs.
     *                                  Values can be strings or Doctrine_Expression instances.
     * @return integer                  the number of affected rows. Boolean false if empty value array was given,
     */
    public function insert(Doctrine_Table $table, array $fields)
    {
        $identifiers = $table->getIdentifierColumnNames();

        $settingNullIdentifier = false;
        $fields = array_change_key_case($fields);
        foreach($identifiers as $identifier) {
            $lcIdentifier = strtolower($identifier);

            if(array_key_exists($lcIdentifier, $fields)) {
                if(is_null($fields[$lcIdentifier])) {
                    $settingNullIdentifier = true;
                    unset($fields[$lcIdentifier]);
                }
            }
        }

        // MSSQL won't allow the setting of identifier columns to null, so insert a default record and then update it
        if ($settingNullIdentifier) {
            $count = $this->exec('INSERT INTO ' . $this->quoteIdentifier($table->getTableName()) . ' DEFAULT VALUES');

            if(! $count) {
                return $count;
            }

            $id = $this->lastInsertId($table->getTableName());

            return $this->update($table, $fields, array($id));
        }

        return parent::insert($table, $fields);
    }
}
