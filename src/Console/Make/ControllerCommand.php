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

class ControllerCommand extends Command
{
    protected static $defaultName = "make:controller";
    protected static $defaultDescription = "Generate a new controller class";

    protected function configure()
    {
        $this
            ->setHelp("Generate a new controller class at {ROOT_DIR}/app/Http/Controllers")
            ->addArgument('name', InputArgument::REQUIRED, 'The controller class name')
            ->addOption(
                'api',
                'a',
                InputOption::VALUE_NONE,
                'Specifies that it should generate an API controller'
            )
            ->addOption(
                'resource',
                'r',
                InputOption::VALUE_NONE,
                'Specifies that it should generate a resource controller'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $api = $input->getOption('api') !== false;
        $resource = $input->getOption('resource') !== false;
        $name = $input->getArgument('name');

        if ($api && $resource) {
            $output->writeln("<error>Passing conflicting options --api/-a and --resource/-r together</error>");
            exit(-1);
        }

        $template = 'controller.php';

        if ($api) {
            $template = 'controller-api.php';
        }

        if ($resource) {
            $template = 'controller-resource.php';
        }

        $this->generator->generate(app()->config["root_dir"] . "/app/Http/Controllers/{$name}.php", $template, function () use ($output, $name) {
            $output->writeln("<info>Controller created</info>: $name");
        }, function ($e) use ($name, $output) {
            $output->writeln("<error>Cannot generate controller</error>: $name.php: <comment>$e</comment>");
            exit(-1);
        }, $name);

        return 0;
    }
}
