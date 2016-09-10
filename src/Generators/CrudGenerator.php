<?php

namespace Tks\Larakits\Generators;

use Exception;
use Illuminate\Filesystem\Filesystem;
use Tks\Larakits\Kits\FormMaker;

/**
 * Generate the CRUD
 */
class CrudGenerator
{
    protected $filesystem;

    protected $config;

    public function __construct()
    {
        $this->filesystem = new Filesystem;
    }
    /**
     * set generate config
     * @param [array] $config [all configs]
     */
    public function setConfig($config)
    {
        $this->config = $config;
    }

    /**
     * Create the controller
     * @param  array $config
     * @return bool
     */
    public function createController()
    {
        if (! is_dir($this->config['_path_controller_'])) mkdir($this->config['_path_controller_'], 0777, true);
     
        $request = file_get_contents($this->config['template_source'].'/Controller.txt');

        foreach ($this->config as $key => $value) {
            $request = str_replace($key, $value, $request);
        }

        $request = file_put_contents($this->config['_path_controller_'].'/'.$this->config['_camel_case_'].'Controller.php', $request);

        return $request;
    }

    /**
     * Create the repository
     * @param  array $config
     * @return bool
     */
    public function createRepository()
    {
        if (! is_dir($this->config['_path_repository_'])) mkdir($this->config['_path_repository_'], 0777, true);
        if (! is_dir($this->config['_path_model_'])) mkdir($this->config['_path_model_'], 0777, true);

        $repo = file_get_contents($this->config['template_source'].'/Repository/Repository.txt');
        $model = file_get_contents($this->config['template_source'].'/Repository/Model.txt');

        if (! empty($this->config['schema'])) {
            $model = str_replace('// _camel_case_ table data', $this->prepareTableDefinition($this->config['schema']), $model);
        }

        foreach ($this->config as $key => $value) {
            $repo = str_replace($key, $value, $repo);
            $model = str_replace($key, $value, $model);
        }

        $repository = file_put_contents($this->config['_path_repository_'].'/'.$this->config['_camel_case_'].'Repository.php', $repo);
        $model = file_put_contents($this->config['_path_model_'].'/'.$this->config['_camel_case_'].'.php', $model);

        return ($repository && $model);
    }

    /**
     * Create the request
     * @return bool
     */
    public function createRequest()
    {
        if (! is_dir($this->config['_path_request_'])) mkdir($this->config['_path_request_'], 0777, true);

        $request = file_get_contents($this->config['template_source'].'/Request.txt');

        foreach ($this->config as $key => $value) {
            $request = str_replace($key, $value, $request);
        }

        $request = file_put_contents($this->config['_path_request_'].'/'.$this->config['_camel_case_'].'Request.php', $request);

        return $request;
    }

    /**
     * Create the service
     * @return bool
     */
    public function createService()
    {
        if (! is_dir($this->config['_path_service_'])) mkdir($this->config['_path_service_'], 0777, true);

        $request = file_get_contents($this->config['template_source'].'/Service.txt');

        foreach ($this->config as $key => $value) {
            $request = str_replace($key, $value, $request);
        }

        $request = file_put_contents($this->config['_path_service_'].'/'.$this->config['_camel_case_'].'Service.php', $request);

        return $request;
    }

    /**
     * Create the routes
     * @param  array $config
     * @return bool
     */
    public function createRoutes($appendRoutes = true)
    {
        if ($appendRoutes) {
            $routesMaster = app_path('Http/routes.php');
        } else {
            $routesMaster = $this->config['_path_routes_'];
        }

        if (! empty($this->config['routes_prefix'])) {
            file_put_contents($routesMaster, $this->config['routes_prefix'], FILE_APPEND);
        }

        $routes = file_get_contents($this->config['template_source'].'/Routes.txt');

        foreach ($this->config as $key => $value) {
            $routes = str_replace($key, $value, $routes);
        }

        file_put_contents($routesMaster, $routes, FILE_APPEND);

        if (! empty($this->config['routes_prefix'])) {
            file_put_contents($routesMaster, $this->config['routes_suffix'], FILE_APPEND);
        }

        return true;
    }

    /**
     * Append to the factory
     * @return bool
     */
    public function createFactory()
    {
        $factory = file_get_contents($this->config['template_source'].'/Factory.txt');

        if (! empty($this->config['schema'])) {
            $factory = str_replace('// _camel_case_ table data', $this->prepareTableExample($this->config['schema']), $factory);
        }

        $factoryMaster = base_path('database/factories/ModelFactory.php');

        foreach ($this->config as $key => $value) {
            $factory = str_replace($key, $value, $factory);
        }

        return file_put_contents($factoryMaster, $factory, FILE_APPEND);
    }

    /**
     * Create the facade
     * @return bool
     */
    public function createFacade()
    {
        if (! is_dir($this->config['_path_facade_'])) mkdir($this->config['_path_facade_']);

        $facade = file_get_contents($this->config['template_source'].'/Facade.txt');

        foreach ($this->config as $key => $value) {
            $facade = str_replace($key, $value, $facade);
        }

        $facade = file_put_contents($this->config['_path_facade_'].'/'.$this->config['_camel_case_'].'.php', $facade);

        return $facade;
    }

    /**
     * Create the tests
     * @param  array $config
     * @return bool
     */
    public function createTests()
    {
        $testMakerResults = [];
        foreach (explode(',', $this->config['tests_generated']) as $testType) {
            $test = file_get_contents($this->config['template_source'].'/Tests/'.ucfirst($testType).'Test.txt');

            if (! empty($this->config['schema'])) {
                $test = str_replace('// _camel_case_ table data', $this->prepareTableExample($this->config['schema']), $test);
            }

            foreach ($this->config as $key => $value) {
                $test = str_replace($key, $value, $test);
            }

            if (! file_put_contents($this->config['_path_tests_'].'/'.$this->config['_camel_case_'].''.ucfirst($testType).'Test.php', $test)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Create the views
     * @return bool
     */
    public function createViews()
    {
        if (! is_dir($this->config['_path_views_'].'/'.$this->config['_lower_casePlural_'])) {
            mkdir($this->config['_path_views_'].'/'.$this->config['_lower_casePlural_']);
        } 
        $formMaker = new FormMaker();
        
        $this->config['_form_elements'] = $formMaker->fromFileDefinition($this->config['path_schema_html_definition'] . '/' . $this->config['_sectionTablePrefix_'].$this->config['_lower_casePlural_'] . '.php');

        foreach (glob($this->config['template_source'].'/Views/*') as $file) {
            $createdView = file_get_contents($file);
            $basename = str_replace('txt', 'php', basename($file));
            foreach ($this->config as $key => $value) {
                $createdView = str_replace($key, $value, $createdView);
            }
            $createdView = file_put_contents($this->config['_path_views_'].'/'.$this->config['_lower_casePlural_'].'/'.$basename, $createdView);
        }

        return ($createdView);
    }

    /**
     * Create the Api
     * @return bool
     */
    public function createApi($appendRoutes = true)
    {
        if ($appendRoutes) {
            $routesMaster = app_path('Http/api-routes.php');
        } else {
            $routesMaster = $this->config['_path_api_routes_'];
        }

        if (! file_exists($routesMaster)) {
            file_put_contents($routesMaster, "<?php\n\n");
        }

        if (! is_dir($this->config['_path_api_controller_'])) {
            mkdir($this->config['_path_api_controller_'], 0777, true);
        }

        $routes = file_get_contents($this->config['template_source'].'/ApiRoutes.txt');

        foreach ($this->config as $key => $value) {
            $routes = str_replace($key, $value, $routes);
        }

        file_put_contents($routesMaster, $routes, FILE_APPEND);

        $request = file_get_contents($this->config['template_source'].'/ApiController.txt');

        foreach ($this->config as $key => $value) {
            $request = str_replace($key, $value, $request);
        }

        $request = file_put_contents($this->config['_path_api_controller_'].'/'.$this->config['_camel_case_'].'Controller.php', $request);

        return $request;
    }
    /**
     * create schema definination file
     */
    public function createSchema($name)
    {
       if (! is_dir($this->config['path_schema_definition'])) {
            mkdir($this->config['path_schema_definition']);
        } 

        if (! is_dir($this->config['path_schema_html_definition'])) {
            mkdir($this->config['path_schema_html_definition']);
        } 

        $schemaContent = file_get_contents($this->config['template_source'].'/schema.txt');
        $schemaHtmlContent = file_get_contents($this->config['template_source'].'/schemaHtml.txt');
        $schemaName = $name.'.php';

        return file_put_contents($this->config['path_schema_definition'].'/'.$schemaName, $schemaContent) && 
               file_put_contents($this->config['path_schema_html_definition'].'/'.$schemaName, $schemaHtmlContent);
    }

    /**
     * Prepare a string of the table
     * @param  string $table
     * @return string
     */
    public function prepareTableDefinition($table)
    {
        $tableDefintion = '';

        foreach (explode(',', $table) as $column) {
            $columnDefinition = explode(':', $column);
            $tableDefintion .= "\t\t'$columnDefinition[0]',\n";
        }

        return $tableDefintion;
    }

    /**
     * Prepare a table array example
     * @param  string $table
     * @return string
     */
    public function prepareTableExample($table)
    {
        $tableExample = '';

        foreach (explode(',', $table) as $key => $column) {
            $columnDefinition = explode(':', $column);
            $example = $this->createExampleByType($columnDefinition[1]);
              if ($key === 0) {
                    $tableExample .= "'$columnDefinition[0]' => '$example',\n";
                } else {
                    $tableExample .= "\t\t'$columnDefinition[0]' => '$example',\n";
                }
        }

        return $tableExample;
    }

    /**
     * Create an example by type for table definitions
     * @param  string  $type
     * @return mixed
     */
    public function createExampleByType($type)
    {
        switch ($type) {
            case 'bigIncrements':           return 1;
            case 'increments':              return 1;
            case 'string':                  return 'laravel';
            case 'boolean':                 return 1;
            case 'binary':                  return 'Its a bird, its a plane, no its Superman!';
            case 'char':                    return 'a';
            case 'ipAddress':               return '192.168.1.1';
            case 'macAddress':              return 'X1:X2:X3:X4:X5:X6';
            case 'json':                    return json_encode(['json' => 'test']);
            case 'text':                    return 'I am Batman';
            case 'longText':                return 'I am Batman';
            case 'mediumText':              return 'I am Batman';
            case 'dateTime':                return date('Y-m-d h:i:s');
            case 'date':                    return date('Y-m-d');
            case 'time':                    return date('h:i:s');
            case 'timestamp':               return time();
            case 'float':                   return 1.1;
            case 'decimal':                 return 1.1;
            case 'double':                  return 1.1;
            case 'integer':                 return 1;
            case 'bigInteger':              return 1;
            case 'mediumInteger':           return 1;
            case 'smallInteger':            return 1;
            case 'tinyInteger':             return 1;

            default:                        return 1;
        }
    }
}
