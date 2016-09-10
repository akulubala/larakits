<?php

namespace Tks\Larakits\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Tks\Larakits\Generators\FileMakerTrait;

class Starter extends Command
{
    use FileMakerTrait;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'larakits:starter';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Larakits will prebuild some common parts of your app';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $fileSystem = new Filesystem;

        $files = $fileSystem->allFiles(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'Starter');

        $this->info("These files will be published");

        foreach ($files as $file) {
            $this->line(str_replace(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'Starter'.DIRECTORY_SEPARATOR, '', $file));
        }

        $result = $this->confirm("Are you sure you want to overwrite any files of the same name?");

        if ($result) {
            $this->line("Copying app/Http...");
            $this->copyPreparedFiles(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'Starter'.DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'Http', app_path('Http'));

            $this->line("Copying app/Repositories...");
             $this->copyPreparedFiles(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'Starter'.DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'Repositories', app_path('Repositories'));

            $this->line("Copying app/Services...");
             $this->copyPreparedFiles(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'Starter'.DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'Services', app_path('Services'));

            $this->line("Copying database...");
             $this->copyPreparedFiles(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'Starter'.DIRECTORY_SEPARATOR.'database', base_path('database'));

            $this->line("Copying resources/views...");
            $this->copyPreparedFiles(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'Starter'.DIRECTORY_SEPARATOR.'resources', base_path('resources'));

            $this->line("Copying tests...");
            $this->copyPreparedFiles(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'Starter'.DIRECTORY_SEPARATOR.'tests', base_path('tests'));
            $this->line("Copying gulpfile...");
            $fileSystem->copy(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'Starter'.DIRECTORY_SEPARATOR.'gulpfile.js', base_path('gulpfile.js'));

            $this->line("Appending database/factory...");
            $this->createFactory();

            $this->info("Update the model in: config/auth.php, database/factory/ModelFactory.php");
            $this->comment("\n");
            $this->comment($this->getAppNamespace()."Repositories\User\User::class");
            $this->comment("\n");

            $this->info("Build something worth sharing!\n");
            $this->info("Don't forget to run:");
            $this->comment("composer dump");
            $this->info("Then:");
            $this->comment("artisan migrate");
        } else {
            $this->info("You cancelled the larakits starter");
        }

    }

    public function createFactory()
    {
        $factory = file_get_contents(__DIR__.'/../Starter/Factory.txt');
        $factoryPrepared = str_replace('{{App\}}', $this->getAppNamespace(), $factory);
        $factoryFile = base_path('database/factories/ModelFactory.php');
        file_put_contents($factoryFile, str_replace($factoryPrepared, '', file_get_contents($factoryFile)));
        return file_put_contents($factoryFile, $factoryPrepared, FILE_APPEND);
    }

}
