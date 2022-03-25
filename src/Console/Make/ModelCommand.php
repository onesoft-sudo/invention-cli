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


use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ModelCommand extends Command
{
    protected static $defaultName = "make:model";
    protected static $defaultDescription = "Generate a new model class";

    protected function configure()
    {
        $this
            ->setHelp("Generate a new model class at {ROOT_DIR}/app/Models")
            ->addArgument('name', InputArgument::REQUIRED, 'The model class name')
            ->addOption(
                'migration',
                'm',
                InputOption::VALUE_NONE,
                'Specifies that it should also generate a migration'
            )
            ->addOption(
                'controller',
                'c',
                InputOption::VALUE_NONE,
                'Specifies that it should also generate a resource controller'
            )
            ->addOption(
                'factory',
                'f',
                InputOption::VALUE_NONE,
                'Specifies that it should also generate a factory'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $migration = $input->getOption('migration') !== false;
        $controller = $input->getOption('controller') !== false;
        $name = $input->getArgument('name');

        $this->generator->generate(app()->config["root_dir"] . "/app/Models/{$name}.php", 'model.php', function () use ($output, $name) {
            $output->writeln("<info>Model created</info>: $name");
        }, function ($e) use ($name, $output) {
            $output->writeln("<error>Cannot generate model</error>: $name.php: <comment>$e</comment>");
            exit(-1);
        }, $name);

        if ($migration) {
            $tbl = strtolower($name);
            $this->getApplication()->find('make:migration')->run(new ArrayInput([
                'name' => "create_{$tbl}s_table",
                'table' => $tbl
            ]), $output);
        }

        if ($controller) {
            $this->getApplication()->find('make:controller')->run(new ArrayInput([
                'name' => "{$name}Controller"
            ]), $output);
        }

        return 0;
    }
}
