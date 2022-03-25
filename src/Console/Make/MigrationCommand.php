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


namespace OSN\Invention\CLI\Console\Make;


use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class MigrationCommand extends Command
{
    protected static $defaultName = "make:migration";
    protected static $defaultDescription = "Generate a new migration class";

    protected function configure()
    {
        $this
            ->setHelp("Generate a new migration class at {ROOT_DIR}/database/migrations/")
            ->addArgument('name', InputArgument::REQUIRED, 'The migration class name')
            ->addArgument('table', InputArgument::REQUIRED, 'The corresponding table name');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $table = $input->getArgument('table');
        $name = "m" . date('Y_m_d_') . rand() . "_" . $input->getArgument('name');

        $this->generator->generate(app()->config["root_dir"] . "/database/migrations/{$name}.php", 'migration.php', function () use ($output, $name) {
            $output->writeln("<info>Migration created</info>: $name");
        }, function ($e) use ($name, $output) {
            $output->writeln("<error>Cannot generate migration</error>: $name.php: <comment>$e</comment>");
            exit(-1);
        }, $name, $table, $table);

        return 0;
    }
}
