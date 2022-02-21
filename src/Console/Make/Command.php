<?php


namespace OSN\Invention\CLI\Console\Make;


use OSN\Invention\Utils\Generator;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Command extends \Symfony\Component\Console\Command\Command
{
    protected static $defaultName = "make:";
    protected static $defaultDescription = "Generate a new class";

    protected Generator $generator;

    public function __construct()
    {
        $this->generator = new Generator();
        parent::__construct();
    }
}
