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


use OSN\Framework\DataTypes\_String;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;

class ComponentCommand extends Command
{
    protected static $defaultName = "make:component";
    protected static $defaultDescription = "Generate a new view component class and view template";

    protected function configure()
    {
        $this
            ->setHelp("Generate a new view component class at {ROOT_DIR}/app/ViewComponents/ and a new view template at {ROOT_DIR}/resources/views/components/")
            ->addArgument('name', InputArgument::REQUIRED, 'The view component name [like a normal PHP class name]');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $name = $input->getArgument('name');
        $viewName = _String::from($name)->camel2slug()->__toString();

        $this->generator->generate(app()->config["root_dir"] . "/app/ViewComponents/{$name}.php", 'component-renderer.php', function () use ($output, $name) {
            $output->writeln("<info>Component class created</info>: $name");
        }, function ($e) use ($name, $output) {
            $output->writeln("<error>Cannot generate component class</error>: $name.php: <comment>$e</comment>");
            exit(-1);
        }, $name, $viewName);

        $this->generator->generate(app()->config["root_dir"] . "/resources/views/components/{$viewName}.power.php", 'component-view.power.php', function () use ($viewName, $output) {
            $output->writeln("<info>Component view template created</info>: $viewName");
        }, function ($e) use ($viewName, $output) {
            $output->writeln("<error>Cannot generate component view template</error>: $viewName.php: <comment>$e</comment>");
            exit(-1);
        });

        return 0;
    }
}
