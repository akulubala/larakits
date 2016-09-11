<?php

namespace Tks\Larakits\Kits;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;

class FormMaker
{
    protected $formElementMaker;

    protected $columnTypes = [
        'integer',
        'string',
        'datetime',
        'date',
        'float',
        'binary',
        'blob',
        'boolean',
        'datetime',
        'time',
        'array',
        'json_array',
        'object',
        'decimal',
        'bigint',
        'smallint',
        'relationship',
    ];

    public function __construct()
    {
        $this->formElementMaker = new FormElementMaker();
    }

    public function fromFileDefinition($schemaDefineFile)
    {
        $schemaHtmlDefinitions = include_once($schemaDefineFile);
        $formHtml = "";
        foreach ($schemaHtmlDefinitions as $column => $type) 
        {
            $formHtml .= $this->formElementMaker->create($column, $type);
        }
        return $formHtml;
    }

    /**
     * Generate a form from a table.
     *
     * @param string $table           Table name
     * @param array  $columns         array columns should 
     * @param string $class           Class names to be given to the inputs
     * @param bool   $populated       Populates the inputs with the column names as values
     * @param bool   $idAndTimestamps Allows id and Timestamp columns
     *
     * @return string
     */
    public function fromTable(
        $table,
        $columns = []
    ) {
        $excludeColumns = ['id', 'updated_at'];
        $formBuild = '';
        $tableColumns = Schema::getColumnListing($table);

        if (empty($columns)) {
            foreach ($tableColumns as $column) {
                $type = DB::connection()->getDoctrineColumn($table, $column)->getType()->getName();
                $columns[$column] = $type;
            }
        }

        foreach ($excludeColumns as $col) {
            unset($columns['col']);
        }

        foreach ($columns as $columnName => $columnType) {
            $input = $this->formElementMaker->create($columnName, $columnType);
            $formBuild .= $this->formBuilder($view, $columnType, $columnName, $input);
        }

        return $formBuild;
    }
    /**
     * Cleanup the ID and TimeStamp columns.
     *
     * @param array $collection
     * @param bool  $timestamps
     * @param bool  $id
     *
     * @return array
     */
    public function cleanupIdAndTimeStamps($collection, $timestamps, $id)
    {
        if (!$timestamps) {
            unset($collection['created_at']);
            unset($collection['updated_at']);
        }

        if (!$id) {
            unset($collection['id']);
        }

        return $collection;
    }

    /**
     * Get Table Columns.
     *
     * @param string $table Table name
     *
     * @return array
     */
    public function getTableColumns($table, $allColumns = false)
    {
        $tableColumns = Schema::getColumnListing($table);

        $tableTypeColumns = [];
        $badColumns = ['id', 'created_at', 'updated_at'];

        if ($allColumns) {
            $badColumns = [];
        }

        foreach ($tableColumns as $column) {
            if (!in_array($column, $badColumns)) {
                $type = DB::connection()->getDoctrineColumn($table, $column)->getType()->getName();
                $tableTypeColumns[$column]['type'] = $type;
            }
        }

        return $tableTypeColumns;
    }
}