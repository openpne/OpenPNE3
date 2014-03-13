<?php

/**
 * Copyright 2010 Kousuke Ebihara
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * PHP Version 5
 *
 * @category  VersionControl
 * @package   VersionControl_Git
 * @author    Kousuke Ebihara <kousuke@co3k.org>
 * @copyright 2010 Kousuke Ebihara
 * @license   http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 */

/**
 * The class represents Git commits
 *
 * @category  VersionControl
 * @package   VersionControl_Git
 * @author    Kousuke Ebihara <kousuke@co3k.org>
 * @copyright 2010 Kousuke Ebihara
 * @license   http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 */
class VersionControl_Git_Object_Commit extends VersionControl_Git_Object
{
    /**
     * The tree object related to this commit
     *
     * @var string
     */
    protected $tree;

    /**
     * The parent object related to this commit
     *
     * @var array
     */
    protected $parent;

    /**
     * The author related to this commit
     *
     * @var string
     */
    protected $author;

    /**
     * The commiter related to this commit
     *
     * @var string
     */
    protected $committer;

    /**
     * The message related to this commit
     *
     * @var string
     */
    protected $message;

    /**
     * The created time of this commit (timestamp)
     *
     * @var int
     */
    protected $createdAt;

    /**
     * The committed time of this commit (timestamp)
     *
     * @var int
     */
    protected $committedAt;

    /**
     * Create an instance of this class that is based on specified array
     *
     * @param VersionControl_Git $git   An instance of the VersionControl_Git
     * @param array              $array An array of properties of this commit
     *
     * @return VersionControl_Git_Object_Commit
     */
    public static function createInstanceByArray($git, $array)
    {
        if (!isset($array['commit']) || !$array['commit']) {
            throw new VersionControl_Git_Exception('The commit object must have id');
        }

        $parts = explode(' ', $array['commit'], 2);

        $id =  $parts[1];

        unset($array['commit']);

        $obj = new VersionControl_Git_Object_Commit($git, $id);

        foreach ($array as $k => $v) {
            $method = 'set'.ucfirst($k);

            if (is_callable(array($obj, $method))) {
                $obj->$method($v);
            }
        }

        return $obj;
    }

    /**
     * Set the tree object related to this commit
     *
     * @param string $tree A tree object
     *
     * @return null
     */
    public function setTree($tree)
    {
        $parts = explode(' ', $tree, 2);

        if (2 != count($parts) || 'tree' !== $parts[0]) {
            return false;
        }

        $this->tree = $parts[1];
    }

    /**
     * Get the tree object related to this commit
     *
     * @return string
     */
    public function getTree()
    {
        return $this->tree;
    }

    /**
     * Set the parent objects related to this commit
     *
     * @param array $parent An array of parent objects
     *
     * @return null
     */
    public function setParents($parent)
    {
        $clean = array();

        foreach ((array)$parent as $v) {
            $parts = explode(' ', $v, 2);
            if (2 != count($parts) || 'parent' !== $parts[0]) {
                return false;
            }

            $clean[] = $parts[1];
        }

        $this->parent = $clean;
    }

    /**
     * Check if this commit has parents or not
     *
     * @return bool
     */
    public function hasParents()
    {
        return (bool)($this->parent);
    }

    /**
     * Get the parent objects related to this commit
     *
     * @return array An array of the VersionControl_Git_Object_Commit
     */
    public function getParents()
    {
        if (!$this->hasParents()) {
            return false;
        }

        $revlists = array();
        foreach ($this->parent as $v) {
            try {
                $revlist = $this->git->getRevListFetcher()
                    ->target($v)
                    ->setOption('max-count', 1)
                    ->fetch();
            } catch (VersionControl_Git_Exception $e) {
                return false;
            }

            $revlists[] = array_shift($revlist);
        }

        return $revlists;
    }

    /**
     * Set the author related to this commit
     *
     * @param string $author A name of author
     *
     * @return null
     */
    public function setAuthor($author)
    {
        $parts = explode(' ', $author, 2);

        if (2 != count($parts) || 'author' !== $parts[0]) {
            return false;
        }

        list ($name, $date) = $this->parseUser($parts[1]);

        $this->author    = $name;
        $this->createdAt = $date;
    }

    /**
     * Get the author related to this commit
     *
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Get the created time of this commit
     *
     * @return int
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set the committer related to this commit
     *
     * @param string $committer A name of committer
     *
     * @return null
     */
    public function setCommitter($committer)
    {
        $parts = explode(' ', $committer, 2);

        if (2 != count($parts) || 'committer' !== $parts[0]) {
            return false;
        }

        list ($name, $date) = $this->parseUser($parts[1]);

        $this->committer   = $name;
        $this->committedAt = $date;
    }

    /**
     * Get the committer related to this commit
     *
     * @return string
     */
    public function getCommitter()
    {
        return $this->committer;
    }

    /**
     * Get the committed time of this commit
     *
     * @return int
     */
    public function getCommittedAt()
    {
        return $this->committedAt;
    }

    /**
     * Set the message related to this commit
     *
     * @param string $message A message of this commit
     *
     * @return null
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * Get the message related to this commit
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Parse a string of commiter and authror line
     *
     * @param string $userAndTimestamp Author or commiter name with timestamp
     *
     * @return array
     */
    protected function parseUser($userAndTimestamp)
    {
        $matches = array();
        if (preg_match('/^(.+) (\d+) .*$/', $userAndTimestamp, $matches)) {
            return array($matches[1], $matches[2]);
        }

        return array(null, null);
    }

    /**
     * Check if this commit has all mandatory attributes or not
     *
     * @return bool
     */
    public function isIncomplete()
    {
        return !(
            $this->tree
            && $this->author
            && $this->committer
            && $this->createdAt
            && $this->committedAt
        );
    }

    /**
     * Fetch the substance of this object
     *
     * If this commit object is not complete, it inserts values to short properties.
     *
     * @return VersionControl_Git_Object The "$this" object for method chain
     */
    public function fetch()
    {
        if ($this->isIncomplete()) {
            try {
                $revlist = $this->git->getRevListFetcher()
                  ->target($this->id)
                  ->setOption('max-count', 1)
                  ->fetch();
            } catch (VersionControl_Git_Exception $e) {
                throw new VersionControl_Git_Exception('The object id is not valid.');
            }

            if (!$this->tree) {
                $this->tree = $revlist[0]->getTree();
            }

            if (!$this->parent) {
                $parents = $revlist[0]->getParents();
                foreach ($parents as $parent) {
                    $this->parents[] = (string)$parent;
                }
            }

            if (!$this->author) {
                $this->author = $revlist[0]->getAuthor();
            }

            if (!$this->committer) {
                $this->committer = $revlist[0]->getCommitter();
            }

            if (!$this->createdAt) {
                $this->createdAt = $revlist[0]->getCreatedAt();
            }

            if (!$this->committedAt) {
                $this->committedAt = $revlist[0]->getCommittedAt();
            }

            if (!$this->message) {
                $this->message = $revlist[0]->getMessage();
            }
        }

        return $this;
    }
}
