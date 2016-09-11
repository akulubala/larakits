<?php

if (!function_exists('form_make_file')) {
    function form_make_file($table)
    {
        return app('FormMaker')->fromFileDefinition($table);
    }
}
