<?php

namespace Tks\Larakits\Kits;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;

class FormElementMaker
{
    protected $elementTypes = ['text', 'textarea', 'hidden', 'password', 'select', 'muti_select', 'radio', 'checkbox', 'file', 'muti_file', 'date', 'datetime'];

    /**
     * [create form html string]
     * @param  [string] $name [form element name]
     * @param  [string] $type [form element type]
     * @return [string]       [form element html]
     */
    public function create($name, $type)
    {
        if (!in_array($type, $this->elementTypes)) {
            throw new Exception("$type is not supported, please change you form defination file", 1);
        } else {
            return call_user_func(array($this, 'make'.ucfirst($type)), $name);
        }
    }

    public function makeText($name)
    {
        $string = <<<EOD
<div class="form-group">
    <label class="col-sm-2 control-label">{{ trans('$name') }}</label>
    <div class="col-sm-10">
        {!! Form::text('$name', null, ["class" => "form-control", "placeholder" => trans('$name')]) !!}
    </div>
</div>\n
EOD;
        return $string;
    }

    public function makeTextarea($name)
    {
        $string = <<<EOD
<div class="form-group">
    <label class="col-sm-2 control-label">{{ trans('$name') }}</label>
    <div class="col-sm-10">
        {!! Form::text('$name', null, ["class" => "form-control", "placeholder" => trans('$name')]) !!}
    </div>
</div>\n
EOD;
        return $string;
    }

    public function makeHidden($name)
    {
        $string = <<<EOD
<div class="form-group">
    <label class="col-sm-2 control-label">{{ trans('$name') }}</label>
    <div class="col-sm-10">
        {!! Form::text('$name', null, ["class" => "form-control", "placeholder" => trans('$name')]) !!}
    </div>
</div>\n
EOD;
        return $string;
    }

    public function makePassword($name)
    {
        $string = <<<EOD
<div class="form-group">
    <label class="col-sm-2 control-label">{{ trans('$name') }}</label>
    <div class="col-sm-10">
        {!! Form::text('$name', null, ["class" => "form-control", "placeholder" => trans('$name')]) !!}
    </div>
</div>\n
EOD;
        return $string;
    }

    public function makeSelect($name)
    {
        $string = <<<EOD
<div class="form-group">
    <label class="col-sm-2 control-label">{{ trans('$name') }}</label>
    <div class="col-sm-10">
        {!! Form::text('$name', null, ["class" => "form-control", "placeholder" => trans('$name')]) !!}
    </div>
</div>\n
EOD;
        return $string;
    }

    public function makeMutiSelect($name)
    {
        $string = <<<EOD
<div class="form-group">
    <label class="col-sm-2 control-label">{{ trans('$name') }}</label>
    <div class="col-sm-10">
        {!! Form::text('$name', null, ["class" => "form-control", "placeholder" => trans('$name')]) !!}
    </div>
</div>\n
EOD;
        return $string;
    }

    public function makeRadio($name)
    {
        $string = <<<EOD
<div class="form-group">
    <label class="col-sm-2 control-label">{{ trans('$name') }}</label>
    <div class="col-sm-10">
        {!! Form::text('$name', null, ["class" => "form-control", "placeholder" => trans('$name')]) !!}
    </div>
</div>\n
EOD;
        return $string;
    }

    public function makeCheckBox($name)
    {
        $string = <<<EOD
<div class="form-group">
    <label class="col-sm-2 control-label">{{ trans('$name') }}</label>
    <div class="col-sm-10">
        {!! Form::text('$name', null, ["class" => "form-control", "placeholder" => trans('$name')]) !!}
    </div>
</div>\n
EOD;
        return $string;
    }

    public function makeFile($name)
    {
        $string = <<<EOD
<div class="form-group">
    <label class="col-sm-2 control-label">{{ trans('$name') }}</label>
    <div class="col-sm-10">
        {!! Form::text('$name', null, ["class" => "form-control", "placeholder" => trans('$name')]) !!}
    </div>
</div>\n
EOD;
        return $string;
    }

    public function makeMutiFile($name)
    {
        $string = <<<EOD
<div class="form-group">
    <label class="col-sm-2 control-label">{{ trans('$name') }}</label>
    <div class="col-sm-10">
        {!! Form::text('$name', null, ["class" => "form-control", "placeholder" => trans('$name')]) !!}
    </div>
</div>\n
EOD;
        return $string;
    }

    public function makeDate($name)
    {
        $string = <<<EOD
<div class="form-group">
    <label class="col-sm-2 control-label">{{ trans('$name') }}</label>
    <div class="col-sm-10">
        {!! Form::text('$name', null, ["class" => "form-control", "placeholder" => trans('$name')]) !!}
    </div>
</div>\n
EOD;
        return $string;
    }

    public function makeDatetime($name)
    {
        $string = <<<EOD
<div class="form-group">
    <label class="col-sm-2 control-label">{{ trans('$name') }}</label>
    <div class="col-sm-10">
        {!! Form::text('$name', null, ["class" => "form-control", "placeholder" => trans('$name')]) !!}
    </div>
</div>\n
EOD;
        return $string;
    }
}