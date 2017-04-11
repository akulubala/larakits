<?php

namespace Tks\Larakits\Console;

use Config;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Tks\Larakits\Generators\CrudGenerator;
use Illuminate\Container\Container;

class Crud extends Command
{

    /**
     * Column Types
     * @var array
     */
    protected $columnTypes = [
        'bigIncrements', 'bigInteger', 'binary', 'boolean', 'char', 'date', 'dateTime', 'dateTimeTz', 'decimal', 'double', 'enum', 'float', 'increments', 'integer', 
            'ipAddress', 'json', 'jsonb', 'longText', 'macAddress', 'mediumIncrements', 'mediumInteger', 'mediumText', 'morphs', 'nullableMorphs', 'nullableTimestamps',
            'rememberToken', 'smallIncrements', 'smallInteger', 'softDeletes', 'string', 'string', 'text', 'time', 'timeTz', 'tinyInteger', 'timestamp', 'timestampTz',
            'timestamps', 'timestampsTz', 'unsignedBigInteger', 'unsignedInteger', 'unsignedMediumInteger', 'unsignedSmallInteger', 'unsignedTinyInteger', 'uuid'
    ];

    /**
     * The console command name.
     * table -> table name
     * --nested  -> nested resouce like resource route post.comment, default false
     * --prefix  -> folder prefix , values ['admin', 'api', 'web']
     * --schema  -> create schema defination default false
     * --migration -> create migration files default true
     * @var string
     */
    protected $signature = 'larakits:crud {table} {--prefix=} {--nested} {--schema} {--translation} {--migration}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a basic CRUD for a table with options for: migration, api, view, and even schema';

    protected $generator;

    protected $filesystem;

    public function __construct()
    {
        parent::__construct();
        $this->generator = new CrudGenerator();
        $this->filesystem = new Filesystem();
    }

    protected function getAppNamespace()
    {
         return Container::getInstance()->getNamespace();
    }

    /**
     * Generate a CRUD stack
     *
     * @return mixed
     */
    public function handle()
    {
        $table = str_plural(str_singular($this->argument('table')));
        $prefix = $this->option('prefix');
        $config = [];
        if ( $this->option('nested') ) {
             /*
             * if nested resource, table must have a comma to seperate
             */
            if (!str_contains($table, '.')) {
                throw new Exception("nested resources table must seperated by comma '.' ;", 1);
            }
            $config = $this->nestedConfig($table, $prefix);

        } else {
            $config = $this->config($table, $prefix);
        }

        $config = $this->formatConfig($config, $table);
        $this->generator->setConfig($config);
        /**
         * generate schema definiation and html definiation files
         */
        if ( $this->option('schema') ) {
            try {
                $this->line('Generating shcema definition files...');
                return  $this->generator->createSchema($table);
            } catch (Exception $e) {
                throw new Exception("Unable to generate schema definition file", 1);
            }
        }
        /**
         * generate migrations
         */
        if ($this->option('migration')) {
            try {
                 $this->line('Building migration...');
                 return $this->generator->createMigrations($table);
            } catch (Exception $e) {
                throw new Exception($e->getMessage(), 1); 
            }
        }
        /**
         * generate translation files, based on schema defination
         */
        if ($this->option('translation')) {
            try {
                 $this->line('Generating translation files files...');
                 return  $this->generator->createTranslation($table);
            } catch (Exception $e) {
                throw new Exception($e->getMessage(), 1);
            }
        }
        

        /**
         * generate CRUD
         */
        if (! file_exists($config['path_schema_definition'] . '/' . $table.'.php') 
            || ! file_exists($config['path_schema_html_definition'] . '/' . $table.'.php')) {
            throw new Exception("Unable to locate schema definition files, please create schema definition file first!", 1);
        }

        $schemaDefinitions = include_once($config['path_schema_definition'] . '/' . $table.'.php');

        foreach ($schemaDefinitions as $column => $columnType) {
            if (str_contains($columnType, '|')) {
                $columnType = explode('|', $columnType)[0];
            }
            if (!in_array($columnType, $this->columnTypes)) {
                throw new Exception("$columnType is not in the array of valid column types: ".implode(', ', $this->columnTypes), 1);
            }
        }

        foreach ($config as $key => $value) {
            if (in_array($key, ['_path_repository_', '_path_model_', '_path_controller_', '_path_api_controller_', '_path_views_', '_path_request_'])) {
                @mkdir($value, 0777, true);
            }
        }

        try {
            $this->line('Building repository...');
            $this->generator->createRepository();

            $this->line('Building request...');
            $this->generator->createRequest();

            $this->line('Building service...');
            $this->generator->createService();

            $this->line('Building controller...');
            $this->generator->createController();

            $this->line('Building routes...');
            $this->generator->createRoutes(false);

            $this->line('Building facade...');
            $this->generator->createFacade();

            $this->line('Building tests...');
            $this->generator->createTests();

            $this->line('Adding to factory...');
            $this->generator->createFactory();

            if ($this->option('prefix') === 'api') {
                $this->line('Building Api...');
                $this->generator->createApi();
            } else {
                $this->line('Building views...');
                $this->generator->createViews($table);

                $this->line('Building leftsidebar...');
                $this->generator->createSideBars();
            }
        } catch (Exception $e) {
            throw new Exception("Unable to generate your CRUD: ".$e->getMessage(), 1);
        }

        $this->info('You may wish to add this as your testing database');
        $this->comment("'testing' => [ 'driver' => 'sqlite', 'database' => ':memory:', 'prefix' => '' ],");
        $this->info('CRUD for '.$table.' is done.'."\n");
    }

    /**
     * Set the config formated
     *
     * @param array $config
     * @param string $section
     * @param string $table
     *
     * @return  array
     */
    public function formatConfig($config, $table)
    {
        if (str_contains($table, '.')) {
            $splitTable = explode('.', $table);
            foreach ($config as $key => $value) {
                $config[$key] = str_replace('_table_', ucfirst($splitTable[1]), str_replace('_section_', ucfirst($splitTable[0]), str_replace('_sectionLowerCase_', strtolower($splitTable[0]), $value)));
            }
        } else {
            foreach ($config as $key => $value) {
                $config[$key] = str_replace('_table_', ucfirst($table), $value);
            }
        }

        return $config;
    }
    /**
     * [nested config  like user.comment]
     * @param  [type] $splitTable [user_roles]
     * @param  [type] $table      [user]
     * @param  [type] $section    [roles]
     * @return [type]             [array of config]
     */
    public function nestedConfig($table, $prefix)
    {
        $splitResource = explode('.', $table);
        return [
                'template_source'            => __DIR__.'/../Templates',
                'path_schema_definition'     => base_path('database/larakits_definition/tables'),
                'path_schema_html_definition'     => base_path('database/larakits_definition/html'),
                'schema'                     => null,
                '_prefix_lower_.'            => strtolower($splitResource[0]).'.',
                '_sectionTablePrefix_'       => strtolower($splitResource[0]).'_',
                '_sectionRoutePrefix_'       => strtolower($splitResource[0]).'/',
                '_sectionNamespace_'         => ucfirst($splitResource[0]).'\\',
                '_path_facade_'              => app_path('Facades'),
                '_path_service_'             => app_path('Services'),
                '_path_repository_'          => app_path('Repositories/'.ucfirst($prefix).'/'.studly_case($splitResource[0]).'/'.ucfirst($table)),
                '_path_model_'               => app_path('Repositories/'.ucfirst($prefix).'/'.studly_case($splitResource[0]).'/'.ucfirst($table)),
                '_path_controller_'          => app_path('Http/Controllers/'.ucfirst($prefix).'/'.studly_case($splitResource[0]).'/'),
                '_path_views_'               => base_path('resources/views/'.ucfirst($prefix).'/'.strtolower($splitResource[0])),
                '_path_tests_'               => base_path('tests'),
                '_path_request_'             => app_path('Http/Requests/'.ucfirst($prefix).'/'.ucfirst($splitResource[0])),
                '_path_routes_'              => base_path('routes/web.php'),
                '_path_api_routes_'          => app_path('routes/api.php'),
                'routes_prefix'              => "\n\nRoute::group(['namespace' => '".ucfirst($prefix).'/'.studly_case($splitResource[0])."', 'prefix' => '".ucfirst($prefix)."', 'middleware' => ['web']], function () { \n",
                'routes_suffix'              => "\n});",
                '_app_namespace_'            => $this->getAppNamespace(),
                '_namespace_services_'       => $this->getAppNamespace().'Services\\'.studly_case($splitResource[0]),
                '_namespace_facade_'         => $this->getAppNamespace().'Facades',
                '_namespace_repository_'     => $this->getAppNamespace().'Repositories\\'.studly_case($splitResource[0]).'\\'.studly_case($table),
                '_namespace_model_'          => $this->getAppNamespace().'Repositories\\'.studly_case($splitResource[0]).'\\'.studly_case($table),
                '_namespace_controller_'     => $this->getAppNamespace().'Http\Controllers\\'.ucfirst($prefix).'/'.studly_case($splitResource[0]),
                '_namespace_request_'        => $this->getAppNamespace().'Http\Requests\\'.ucfirst($prefix).'/'.studly_case($splitResource[0]),
                '_resource_name_'            => strtolower($table),
                '_lower_case_'               => strtolower($table),
                '_lower_casePlural_'         => str_plural(strtolower($table)),
                '_camel_case_'               => ucfirst(camel_case($table)),
                '_camel_caseSingular'        => str_singular(camel_case($table)),
                'tests_generated'            => 'integration,service,repository',
            ];
    }

    /**
     * @param  [type] $table [user]
     * @return [type]        [array of config]
     */
    public function config($table, $prefix)
    {
        return [
            'template_source'            => __DIR__.'/../Templates',
            'path_schema_definition'     => base_path('database/larakits_definition/tables'),
            'path_schema_html_definition'     => base_path('database/larakits_definition/html'),
            'schema'                     => null,
            '_prefix_lower_'            => strtolower($prefix),
            '_sectionTablePrefix_'       => '',
            '_sectionRoutePrefix_'       => '',
            '_sectionNamespace_'         => '',
            '_path_facade_'              => app_path('Facades'),
            '_path_service_'             => app_path('Services'),
            '_path_repository_'          => app_path('Repositories/' . studly_case($table)),
            '_path_model_'               => app_path('Repositories/' . studly_case($table)),
            '_path_controller_'          => app_path('Http/Controllers/' . ucfirst($prefix)),
            '_path_views_'               => base_path('resources/views/' . ucfirst($prefix)),
            '_path_tests_'               => base_path('tests'),
            '_path_request_'             => app_path('Http/Requests/') . ucfirst($prefix),
            '_path_routes_'              => base_path('routes/web.php'),
            '_path_api_routes_'          => base_path('routes/api.php'),
            'routes_prefix'              => '',
            'routes_suffix'              => '',
            '_app_namespace_'            => $this->getAppNamespace(),
            '_namespace_services_'       => $this->getAppNamespace().'Services',
            '_namespace_facade_'         => $this->getAppNamespace().'Facades',
            '_namespace_repository_'     => $this->getAppNamespace().'Repositories\\' . studly_case($table),
            '_namespace_model_'          => $this->getAppNamespace().'Repositories\\' . studly_case($table),
            '_namespace_controller_'     => $this->getAppNamespace().'Http\Controllers\\' . ucfirst($prefix),
            '_namespace_request_'        => $this->getAppNamespace().'Http\Requests\\'. ucfirst($prefix),
            '_resource_name_'            => strtolower($table),
            '_lower_case_'               => strtolower($table),
            '_lower_casePlural_'         => str_plural(strtolower($table)),
            '_camel_case_'               => ucfirst(camel_case($table)),
            '_camel_caseSingular'        => str_singular(camel_case($table)),
            'tests_generated'            => 'integration,service,repository',
        ];
    }

}
