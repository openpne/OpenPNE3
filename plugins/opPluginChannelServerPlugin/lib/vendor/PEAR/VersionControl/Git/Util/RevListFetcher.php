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
 * The class represents Git rev-list
 *
 * @category  VersionControl
 * @package   VersionControl_Git
 * @author    Kousuke Ebihara <kousuke@co3k.org>
 * @copyright 2010 Kousuke Ebihara
 * @license   http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 */
class VersionControl_Git_Util_RevListFetcher extends VersionControl_Git_Util_Command
{
    /**
     * The default target value
     *
     * @var string
     */
    const DEFAULT_TARGET = 'master';

    /**
     * The target for the commit (commit range string, branch name, etc...)
     *
     * @var string
     */
    protected $target = self::DEFAULT_TARGET;

    /**
     * Set the target
     *
     * @param string $target The target for the commits that you want to get
     *
     * @return VersionControl_Git_Util_RevListFetcher The "$this" object
     */
    public function target($target)
    {
        $this->target = $target;

        return $this;
    }

    /**
     * Reset properties
     *
     * @return VersionControl_Git_Util_RevListFetcher The "$this" object
     */
    public function reset()
    {
        $this->options = array();

        $this->target = self::DEFAULT_TARGET;

        return $this;
    }

    /**
     * Fetch the commits
     *
     * @return array An array of instances of VersionControl_Git_Object_Commit
     */
    public function fetch()
    {
        $string = $this->setSubCommand('rev-list')
          ->setOption('pretty', 'raw')
          ->setArguments(array($this->target))
          ->execute();

        $lines = explode("\n", $string);

        $this->reset();

        $commits = array();

        while (count($lines)) {
            $commit = array_shift($lines);
            if (!$commit) {
                continue;
            }

            $tree = array_shift($lines);

            $parents = array();
            while (count($lines) && 0 === strpos($lines[0], 'parent')) {
                $parents[] = array_shift($lines);
            }

            $author    = array_shift($lines);
            $committer = array_shift($lines);

            $message = array();
            array_shift($lines);
            while (count($lines) && 0 === strpos($lines[0], '   ')) {
                $message[] = trim(array_shift($lines));
            }
            array_shift($lines);

            $commits[] = VersionControl_Git_Object_Commit::createInstanceByArray($this->git, array(
                'commit' => $commit,
                'tree' => $tree,
                'parents' => $parents,
                'author' => $author,
                'committer' => $committer,
                'message' => implode("\n", $message),
            ));
        }

        return $commits;
    }
}
