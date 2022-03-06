<?php


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
