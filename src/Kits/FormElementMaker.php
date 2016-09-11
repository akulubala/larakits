<?php

namespace Tks\Larakits\Kits;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;

class FormElementMaker
{
    protected $elementTypes = [
        'text', 
        'textarea', 
        'hidden', 
        'password', 
        'select', 
        'muti_select', 
        'radio', 
        'checkbox', 
        'file', 
        'muti_file', 
        'date', 
        'datetime',
    ];

    /**
     * [create form html string]
     * @param  [string] $name [form element name]
     * @param  [string] $type [form element type]
     * @return [string]       [form element html]
     */
    public function create($name, $type)
    {
        $extras = null;
        if (str_contains($type, '|')) {
            $typeArrays = explode('|', $type);
            $type = $typeArrays[0];
            $extras = $typeArrays[1];
        }
        if (!in_array($type, $this->elementTypes)) {
            throw new \Exception("$type is not supported, please change you form defination file", 1);
        } else {
            return call_user_func(array($this, 'make'.ucfirst($type)), $name, $extras);
        }
    }
    /**
     * [makeText make text input]
     * @param  [type] $name   [description]
     * @param  [type] $extras [no need in this function]
     * @return [type]         [description]
     */
    public function makeText($name, $extras = null)
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
    /**
     * [makeTextarea description]
     * @param  [type] $name   [description]
     * @param  [type] $extras [description]
     * @return [type]         [description]
     */
    public function makeTextarea($name, $extras = null)
    {
        $string = <<<EOD
<div class="form-group">
    <label class="col-sm-2 control-label">{{ trans('$name') }}</label>
    <div class="col-sm-10">
        {!! Form::textarea('$name', null, ["class" => "form-control", "placeholder" => trans('$name')]) !!}
    </div>
</div>\n
EOD;
        return $string;
    }

    public function makeHidden($name, $extras = null)
    {
        $string = <<<EOD
<div class="form-group">
    <label class="col-sm-2 control-label">{{ trans('$name') }}</label>
    <div class="col-sm-10">
        {!! Form::hidden('$name', null, ["class" => "form-control", "placeholder" => trans('$name')]) !!}
    </div>
</div>\n
EOD;
        return $string;
    }

    public function makePassword($name, $extras = null)
    {
        $string = <<<EOD
<div class="form-group">
    <label class="col-sm-2 control-label">{{ trans('$name') }}</label>
    <div class="col-sm-10">
        {!! Form::password('$name', null, ["class" => "form-control", "placeholder" => trans('$name')]) !!}
    </div>
</div>\n
EOD;
        return $string;
    }

    public function makeSelect($name, $extras)
    {
        $relationShips = explode(':', $extras);
        $relationType = $relationShips[0];
        $selectValues = $relationShips[1];

        $string = <<<EOD
<div class="form-group">
    <label class="col-sm-2 control-label">{{ trans('$name') }}</label>
    <div class="col-sm-10">
        {!! Form::select('$name', '$selectValues', null, ["class" => "form-control select2", "placeholder" => trans('$name')]) !!}
    </div>
</div>\n
EOD;
        return $string;
    }

    public function makeMutiSelect($name, $extras = null)
    {
        $relationShips = explode(':', $extras);
        $relationType = $relationShips[0];
        $selectValues = $relationShips[1];

        $string = <<<EOD
<div class="form-group">
    <label class="col-sm-2 control-label">{{ trans('$name') }}</label>
    <div class="col-sm-10">
        {!! Form::text('$name\[\]', null, ["class" => "form-control", "placeholder" => trans('$name')]) !!}
    </div>
</div>\n
EOD;
        return $string;
    }

    public function makeRadio($name, $extras = null)
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

    public function makeCheckBox($name, $extras = null)
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

    public function makeFile($name, $extras = null)
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

    public function makeMutiFile($name, $extras = null)
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

    public function makeDate($name, $extras = null)
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

    public function makeDatetime($name, $extras = null)
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