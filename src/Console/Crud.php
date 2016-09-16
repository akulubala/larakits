<?php

namespace Tks\Larakits\Console;

use Config;
use Artisan;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Tks\Larakits\Generators\CrudGenerator;
use Illuminate\Console\AppNamespaceDetectorTrait;

class Crud extends Command
{
    use AppNamespaceDetectorTrait;

    /**
     * Column Types
     * @var array
     */
    protected $columnTypes = [
        'bigIncrements',
        'increments',
        'bigInteger',
        'binary',
        'boolean',
        'char',
        'date',
        'dateTime',
        'decimal',
        'double',
        'enum',
        'float',
        'integer',
        'ipAddress',
        'json',
        'jsonb',
        'longText',
        'macAddress',
        'mediumInteger',
        'mediumText',
        'morphs',
        'smallInteger',
        'string',
        'string',
        'text',
        'time',
        'tinyInteger',
        'timestamp',
        'uuid',
    ];

    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'larakits:crud {table} {--schema} {--api} {--migration}';

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

    /**
     * Generate a CRUD stack
     *
     * @return mixed
     */
    public function handle()
    {
        $section = false;
        $table = ucfirst(str_singular($this->argument('table')));
        if (stristr($table, '_')) {
            $splitTable = explode('_', $table);
            $table = $splitTable[1];
            $section = $splitTable[0];
        }

        $config = [];

        if (!$section) {
            $config = $this->noneSectionConfig($table);
        } else {
            $config = $this->sectionConfig($splitTable, $table, $section);
        }

        $config = $this->setConfig($config, $section, $table);
        $this->generator->setConfig($config);

        $schemaName = strtolower(studly_case($this->argument('table')));
        if ($this->option('schema')) {
            try {
                $this->line('Generating shcema definition files...');
                return $this->buildSchemadefinition($schemaName);
            } catch (Exception $e) {
                throw new Exception("Unable to generate schema definition file", 1);
            }
        }

        if (! file_exists($config['path_schema_definition'] . '/' . $schemaName.'.php') 
            || ! file_exists($config['path_schema_html_definition'] . '/' . $schemaName.'.php')) {
            throw new Exception("Unable to locate schema definition files, please create schema definition file first!", 1);
        }

        $schemaDefinitions = include_once($config['path_schema_definition'] . '/' . $schemaName.'.php');

        foreach ($schemaDefinitions as $column => $columnType) {
            if (!in_array($columnType, $this->columnTypes)) {
            throw new Exception("$columnType is not in the array of valid column types: ".implode(', ', $this->columnTypes), 1);
            }
        }

        foreach ($config as $key => $value) {
            if (in_array($key, ['_path_repository_', '_path_model_', '_path_controller_', '_path_api_controller_', '_path_views_', '_path_request_',])) {
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

            $this->line('Building views...');
            $this->generator->createViews();

            $this->line('Building leftsidebar...');
            $this->generator->createSideBars();

            $this->line('Building routes...');
            $this->generator->createRoutes(false);

            $this->line('Building facade...');
            $this->generator->createFacade();


            $this->line('Building tests...');
            $this->generator->createTests();

            $this->line('Adding to factory...');
            $this->generator->createFactory();

            if ($this->option('api')) {
                $this->line('Building Api...');
                $this->comment("\nAdd the following to your app/Providers/RouteServiceProvider.php: \n");
                $this->info("require app_path('Http/api-routes.php'); \n");
                $this->generator->createApi();
            }

        } catch (Exception $e) {
            throw new Exception("Unable to generate your CRUD: ".$e->getMessage(), 1);
        }

        try {
            if ($this->option('migration')) {
                $this->line('Building migration...');
                if ($section) {
                    $migrationName = 'create_'.str_plural(strtolower(implode('_', $splitTable))).'_table';
                    Artisan::call('make:migration', [
                        'name' => $migrationName,
                        '--table' => str_plural(strtolower(implode('_', $splitTable))),
                        '--create' => true,
                    ]);
                } else {
                    $migrationName = 'create_'.str_plural(strtolower($table)).'_table';
                    Artisan::call('make:migration', [
                        'name' => $migrationName,
                        '--table' => str_plural(strtolower($table)),
                        '--create' => true,
                    ]);
                }

                if (!empty($schemaDefinitions)) {
                    $migrationFiles = $this->filesystem->allFiles(base_path('database/migrations'));
                    foreach ($migrationFiles as $file) {
                        if (stristr($file->getBasename(), $migrationName) ) {
                            $migrationData = file_get_contents($file->getPathname());
                            $parsedTable = "";
                            $i = 0;
                            foreach ($schemaDefinitions as $column => $type) {
                                if ($i === 0) {
                                    $parsedTable .= "\$table->$type('$column');\n";
                                } else {
                                    $parsedTable .= "\t\t\t\$table->$type('$column');\n";
                                }
                                $i++;
                            }

                            $migrationData = str_replace("\$table->increments('id');", $parsedTable, $migrationData);
                            file_put_contents($file->getPathname(), $migrationData);
                        }
                    }
                }
            } else {
                $this->info("\nYou will want to create a migration in order to get the $table tests to work correctly.\n");
            }
        } catch (Exception $e) {
            throw new Exception("Could not process the migration but your CRUD was generated", 1);
        }

        $this->info('You may wish to add this as your testing database');
        $this->comment("'testing' => [ 'driver' => 'sqlite', 'database' => ':memory:', 'prefix' => '' ],");
        $this->info('CRUD for '.$table.' is done.'."\n");
    }

    /**
     * create schema definition file, after that create table based on schema file
     * @return void
     */
    public function buildSchemadefinition($schema)
    {
        $this->generator->createSchema($schema);
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
    public function setConfig($config, $section, $table)
    {
        if (! is_null($section)) {
            foreach ($config as $key => $value) {
                $config[$key] = str_replace('_table_', ucfirst($table), str_replace('_section_', ucfirst($section), str_replace('_sectionLowerCase_', strtolower($section), $value)));
            }
        } else {
            foreach ($config as $key => $value) {
                $config[$key] = str_replace('_table_', ucfirst($table), $value);
            }
        }

        return $config;
    }
    /**
     * [sectionConfig a relational table like user_roles, section will be roles]
     * @param  [type] $splitTable [user_roles]
     * @param  [type] $table      [user]
     * @param  [type] $section    [roles]
     * @return [type]             [array of config]
     */
    public function sectionConfig($splitTable, $table, $section)
    {
        return [
                'template_source'            => __DIR__.'/../Templates',
                'path_schema_definition'     => base_path('database/larakits_definition/database'),
                'path_schema_html_definition'     => base_path('database/larakits_definition/html'),
                'schema'                     => null,
                '_sectionPrefix_'            => strtolower($section).'.',
                '_sectionTablePrefix_'       => strtolower($section).'_',
                '_sectionRoutePrefix_'       => strtolower($section).'/',
                '_sectionNamespace_'         => ucfirst($section).'\\',
                '_path_facade_'              => app_path('Facades'),
                '_path_service_'             => app_path('Services'),
                '_path_repository_'          => app_path('Repositories/'.ucfirst($section).'/'.ucfirst($table)),
                '_path_model_'               => app_path('Repositories/'.ucfirst($section).'/'.ucfirst($table)),
                '_path_controller_'          => app_path('Http/Controllers/'.ucfirst($section).'/'),
                '_path_api_controller_'      => app_path('Http/Controllers/Api/'.ucfirst($section).'/'),
                '_path_views_'               => base_path('resources/views/'.strtolower($section)),
                '_path_tests_'               => base_path('tests'),
                '_path_request_'             => app_path('Http/Requests/'.ucfirst($section)),
                '_path_routes_'              => app_path('Http/routes.php'),
                '_path_api_routes_'          => app_path('Http/api-routes.php'),
                'routes_prefix'              => "\n\nRoute::group(['namespace' => '".ucfirst($section)."', 'prefix' => '".strtolower($section)."', 'middleware' => ['web']], function () { \n",
                'routes_suffix'              => "\n});",
                '_app_namespace_'            => $this->getAppNamespace(),
                '_namespace_services_'       => $this->getAppNamespace().'Services\\'.ucfirst($section),
                '_namespace_facade_'         => $this->getAppNamespace().'Facades',
                '_namespace_repository_'     => $this->getAppNamespace().'Repositories\\'.ucfirst($section).'\\'.ucfirst($table),
                '_namespace_model_'          => $this->getAppNamespace().'Repositories\\'.ucfirst($section).'\\'.ucfirst($table),
                '_namespace_controller_'     => $this->getAppNamespace().'Http\Controllers\\'.ucfirst($section),
                '_namespace_api_controller_' => $this->getAppNamespace().'Http\Controllers\Api\\'.ucfirst($section),
                '_namespace_request_'        => $this->getAppNamespace().'Http\Requests\\'.ucfirst($section),
                '_table_name_'               => str_plural(strtolower(implode('_', $splitTable))),
                '_lower_case_'               => strtolower($table),
                '_lower_casePlural_'         => str_plural(strtolower($table)),
                '_camel_case_'               => ucfirst(camel_case($table)),
                '_camel_casePlural_'         => str_plural(camel_case($table)),
                '_ucCamel_casePlural_'       => ucfirst(str_plural(camel_case($table))),
                'tests_generated'            => 'integration,service,repository',
            ];
    }

    /**
     * [noneSectionConfig user]
     * @param  [type] $table [user]
     * @return [type]        [array of config]
     */
    public function noneSectionConfig($table)
    {
        return [
            'template_source'            => __DIR__.'/../Templates',
            'path_schema_definition'     => base_path('database/larakits_definition/database'),
            'path_schema_html_definition'     => base_path('database/larakits_definition/html'),
            'schema'                     => null,
            '_sectionPrefix_'            => '',
            '_sectionTablePrefix_'       => '',
            '_sectionRoutePrefix_'       => '',
            '_sectionNamespace_'         => '',
            '_path_facade_'              => app_path('Facades'),
            '_path_service_'             => app_path('Services'),
            '_path_repository_'          => app_path('Repositories/_table_'),
            '_path_model_'               => app_path('Repositories/_table_'),
            '_path_controller_'          => app_path('Http/Controllers/'),
            '_path_api_controller_'      => app_path('Http/Controllers/Api'),
            '_path_views_'               => base_path('resources/views'),
            '_path_tests_'               => base_path('tests'),
            '_path_request_'             => app_path('Http/Requests/'),
            '_path_routes_'              => app_path('Http/routes.php'),
            '_path_api_routes_'          => app_path('Http/api-routes.php'),
            'routes_prefix'              => '',
            'routes_suffix'              => '',
            '_app_namespace_'            => $this->getAppNamespace(),
            '_namespace_services_'       => $this->getAppNamespace().'Services',
            '_namespace_facade_'         => $this->getAppNamespace().'Facades',
            '_namespace_repository_'     => $this->getAppNamespace().'Repositories\_table_',
            '_namespace_model_'          => $this->getAppNamespace().'Repositories\_table_',
            '_namespace_controller_'     => $this->getAppNamespace().'Http\Controllers',
            '_namespace_api_controller_' => $this->getAppNamespace().'Http\Controllers\Api',
            '_namespace_request_'        => $this->getAppNamespace().'Http\Requests',
            '_table_name_'               => str_plural(strtolower($table)),
            '_lower_case_'               => strtolower($table),
            '_lower_casePlural_'         => str_plural(strtolower($table)),
            '_camel_case_'               => ucfirst(camel_case($table)),
            '_camel_casePlural_'         => str_plural(camel_case($table)),
            '_ucCamel_casePlural_'       => ucfirst(str_plural(camel_case($table))),
            'tests_generated'            => 'integration,service,repository',
        ];
    }

}
