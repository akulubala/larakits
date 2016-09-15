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
       $first = null;
       $second = null;
       $third = null;
        if (str_contains($type, '|')) {
            $typeArrays = explode('|', $type);
            if (count($typeArrays) > 4) {
                throw new \Exception("$type have too much extras params!", 1);
            }
            $type = $typeArrays[0];
            $first = isset($typeArrays[1]) ? $typeArrays[1] : null;
            $second = isset($typeArrays[2]) ? $typeArrays[2] : null;
            $third = isset($typeArrays[3]) ? $typeArrays[3] : null;

        }
        if (!in_array($type, $this->elementTypes)) {
            throw new \Exception("$type is not supported, please change you form defination file", 1);
        } else {
            return call_user_func(array($this, 'make'.ucfirst($type)), $name, $first, $second, $third);
        }
    }
    /**
     * [makeText make text input]
     * @param  [type] $name   [description]
     * @param  [type] $extras [no need in this function]
     * @return [type]         [description]
     */
    public function makeText($name, $first = null, $second = null, $third = null)
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
    public function makeTextarea($name, $first = null, $second = null, $third = null)
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

    public function makeHidden($name, $first = null, $second = null, $third = null)
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

    public function makePassword($name, $first = null, $second = null, $third = null)
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

    public function makeSelect($name, $dropDownValues, $second = null, $third = null)
    {
        $string = <<<EOD
<div class="form-group">
    <label class="col-sm-2 control-label">{{ trans('$name') }}</label>
    <div class="col-sm-10">
        {!! Form::select('$name', $dropDownValues, null, ["class" => "form-control select2", "placeholder" => trans('$name')]) !!}
    </div>
</div>\n
EOD;
        return $string;
    }

    public function makeMutiSelect($name, $dropDownValues, $selectedValues = null, $third = null)
    {
        $string = <<<EOD
<div class="form-group">
    <label class="col-sm-2 control-label">{{ trans('$name') }}</label>
    <div class="col-sm-10">
        {!! Form::select('$name\[\]', $dropDownValues, is_null($selectedValue) ? null : $selectedValues, ["class" => "form-control select2","mutiple" => "mutiple" "placeholder" => trans('$name')]) !!}
    </div>
</div>\n
EOD;
        return $string;
    }

    public function makeRadio($name, $value = null, $checked = false, $third = null)
    {
        $string = <<<EOD
<div class="form-group">
    <label class="col-sm-2 control-label">{{ trans('$name') }}</label>
    <div class="col-sm-10">
        {!! Form::radio('$name', '$value', $checked, ["class" => "form-control", "placeholder" => trans('$name')]) !!}
    </div>
</div>\n
EOD;
        return $string;
    }

    public function makeCheckBox($name, $value = null, $checked = false, $third = null)
    {
        $string = <<<EOD
<div class="form-group">
    <label class="col-sm-2 control-label">{{ trans('$name') }}</label>
    <div class="col-sm-10">
        {!! Form::text('$name', '$value', $checked, ["class" => "form-control", "placeholder" => trans('$name')]) !!}
    </div>
</div>\n
EOD;
        return $string;
    }

    public function makeFile($name, $first = null, $second = null, $third = null)
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

    public function makeMutiFile($name, $first = null, $second = null, $third = null)
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

    public function makeDate($name, $first = null, $second = null, $third = null)
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

    public function makeDatetime($name, $first = null, $second = null, $third = null)
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