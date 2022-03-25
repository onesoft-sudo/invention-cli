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
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;

class TestCommand extends Command
{
    protected static $defaultName = "make:test";
    protected static $defaultDescription = "Generate a new test class";

    protected function configure()
    {
        $this
            ->setHelp("Generate a new test class at {ROOT_DIR}/tests/")
            ->addArgument('name', InputArgument::REQUIRED, 'The test class name')
            ->addOption(
                'feature',
                'f',
                InputOption::VALUE_NONE,
                'Specifies that it should generate a feature test'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $feature = $input->getOption('feature') !== false;
        $name = $input->getArgument('name');
        $path = $feature ? 'Feature' : 'Unit';

        $this->generator->generate(app()->config["root_dir"] . "/tests/$path/{$name}.php", 'test.php', function () use ($output, $name) {
            $output->writeln("<info>Test created</info>: $name");
        }, function ($e) use ($name, $output) {
            $output->writeln("<error>Cannot generate test</error>: $name.php: <comment>$e</comment>");
            exit(-1);
        }, $path, $feature ? 'Tests' : 'PHPUnit\\Framework', $name);

        return 0;
    }
}
