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


namespace OSN\Invention\CLI\Console\Migrate;

use OSN\Invention\CLI\Utils\Migrations;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class MigrateCommand extends Command
{
    protected static $defaultName = "migrate";
    protected static $defaultDescription = "Run the migrations";

    protected Migrations $migrations;

    public function __construct()
    {
        $this->migrations = new Migrations();
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setHelp("Run all the migrations.")
            ->addOption(
                'seed',
                's',
                InputOption::VALUE_NONE,
                "Run the seeders after migration"
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        global $argv;

        $this->migrations->applyAll(function ($c) use ($output) {
            $output->writeln("<info>Applying migration:</info> <comment>$c</comment>");
        }, function ($c) use ($output) {
            $output->writeln("<info>Applied migration:</info> <comment>$c</comment>");
        }, function ($c) use ($output) {
            $output->writeln("<info>Migration is already up:</info> <comment>$c</comment>");
        });

        if ($input->getOption('seed') !== false) {
            $output->writeln('');
            $this->getApplication()->find('db:seed')->run(new ArrayInput([]), $output);
        }

        return 0;
    }
}
