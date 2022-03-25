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

namespace OSN\Invention\CLI\Console\DB;


use OSN\Framework\Database\Seeder;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SeedCommand extends Command
{
    protected static $defaultName = "db:seed";
    protected static $defaultDescription = "Run the seeders";
    public static array $seeded = [];
    public static OutputInterface $output;

    protected function configure()
    {
        $this
            ->setHelp("Run the database seeders");
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        static::$output = $output;
        $seeders = scandir(basepath('/database/seeders'));

        foreach ($seeders as $seeder) {
            if (!is_dir($seeder)) {
                $seederClass = "Database\\Seeders\\" . pathinfo($seeder, PATHINFO_FILENAME);
                $this->seedOne($seederClass);
            }
        }

        return 0;
    }

    public static function seedOne(string $seederClass)
    {
        if (in_array($seederClass, static::$seeded))
            return;

        /** @var Seeder $seederObject */
        $seederObject = new $seederClass();
        static::$output->writeln("\033[1;33mSeeding: \033[0m" . $seederClass);
        $seederObject->seed();
        static::$output->writeln("\033[1;32mSeeded: \033[0m" . $seederClass);
        static::$seeded[] = $seederClass;
    }
}
