<?php
/*
 * Copyright 2020-2022 OSN Software Foundation, Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace OSN\Invention\CLI\Utils;

use Closure;
use OSN\Framework\Console\App;
use OSN\Framework\Core\Database;
use OSN\Framework\Core\Migration;

class Migrations
{
    protected string $path;
    protected array $migrations = [];
    protected Database $db;

    public function __construct()
    {
        $this->path = App::$app->config["root_dir"] . "/database/migrations/";
        $this->migrations = scandir($this->path);
        $this->db = App::db();
    }

    public function applyAll(Closure $callback1 = null, Closure $callback2 = null, Closure $callback3 = null)
    {
        asort($this->migrations);
        foreach ($this->migrations as $migrationFile) {
            if (is_dir($migrationFile))
                continue;

            $migrationClass = explode(".", $migrationFile)[0];

            if ($callback1 !== null)
                $callback1($migrationClass);

            include_once $this->path . $migrationFile;

            /** @var Migration $migration */
            $migration = new $migrationClass();

            if($migration->up($this->db) === false) {
                if ($callback3 !== null)
                    $callback3($migrationClass);

                continue;
            }

            if ($callback2 !== null)
                $callback2($migrationClass);
        }
    }

    public function reset(Closure $callback1 = null, Closure $callback2 = null, Closure $callback3 = null)
    {
        rsort($this->migrations);
        foreach ($this->migrations as $migrationFile) {
            if (is_dir($migrationFile))
                continue;

            $migrationClass = explode(".", $migrationFile)[0];

            if ($callback1 !== null)
                $callback1($migrationClass);

            include_once $this->path . $migrationFile;

            $migration = new $migrationClass();

            if($migration->down($this->db) === false) {
                if ($callback3 !== null)
                    $callback3($migrationClass);

                continue;
            }

            if ($callback2 !== null)
                $callback2($migrationClass);
        }
    }
}
