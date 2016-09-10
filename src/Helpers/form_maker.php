<?php

if (!function_exists('form_make_file')) {
    function form_make_file($table)
    {
        return app('FormMaker')->fromFileDefinition($table);
    }
}

if (! function_exists('form_maker_table')) {
    function form_maker_table($table, $columns = [])
    {
        return app('FormMaker')->fromTable($table, $columns = []);
    }
}

if (! function_exists('form_maker_object')) {
    function form_maker_object($object, $columns = null, $view = null, $class = 'form-control', $populated = true, $reformatted = false, $idAndTimestamps = false)
    {
        return app('FormMaker')->fromObject($object, $columns, $view, $class, $populated, $reformatted, $idAndTimestamps);
    }
}

if (! function_exists('form_maker_array')) {
    function form_maker_array($array, $columns = null, $view = null, $class = 'form-control', $populated = true, $reformatted = false, $idAndTimestamps = false)
    {
        return app('FormMaker')->fromArray($array, $columns, $view, $class, $populated, $reformatted, $idAndTimestamps);
    }
}

if (! function_exists('form_maker_columns')) {
    function form_maker_columns($table)
    {
        return app('FormMaker')->getTableColumns($table);
    }
}
