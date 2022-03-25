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


use OSN\Invention\Utils\Generator;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CliScriptCommand extends Command
{
    protected static $defaultName = "make:cli-script";
    protected static $defaultDescription = "Generate a new CLI script";

    protected function configure()
    {
        $this
            ->setHelp("Generate a new CLI script at {ROOT_DIR}")
            ->addArgument('name', InputArgument::REQUIRED, 'The CLI script name');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $name = $input->getArgument('name');

        $this->generator->generate(app()->config["root_dir"] . "/{$name}", 'cli-script.php', function () use ($output, $name) {
            @chmod(app()->config["root_dir"] . "/{$name}", 0755);
            $output->writeln("<info>CLI script created</info>: $name");
        }, function ($e) use ($name, $output) {
            $output->writeln("<error>Cannot generate CLI script</error>: $name.php: <comment>$e</comment>");
            exit(-1);
        }, $name);

        return 0;
    }
}
