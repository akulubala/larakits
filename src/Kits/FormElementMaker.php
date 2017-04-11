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
        'date_time',
    ];

    /**
     * [create form html string]
     * @param  [string] $name [form element name]
     * @param  [string] $type [form element type]
     * @return [string]       [form element html]
     */
    public function create($name, $resouce, $type)
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
            return call_user_func(array($this, 'make'.studly_case($type)), $name, $resouce, $first, $second, $third);
        }
    }
    /**
     * [makeText make text input]
     * @param  [type] $name   [description]
     * @param  [type] $extras [no need in this function]
     * @return [type]         [description]
     */
    public function makeText($name, $resouce, $first = null, $second = null, $third = null)
    {
        $string = <<<EOD
<div class="form-group">
    <label class="col-sm-2 control-label">{{ trans('$resouce.$name') }}<i class="text-red">&nbsp;*</i></label>
    <div class="col-sm-8">
        {!! Form::text('$name', null, ["class" => "form-control", "placeholder" => trans('$resouce.$name')]) !!}
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
    public function makeTextarea($name, $resouce, $first = null, $second = null, $third = null)
    {
        $string = <<<EOD
<div class="form-group">
    <label class="col-sm-2 control-label">{{ trans('$resouce.$name') }}<i class="text-red">&nbsp;&nbsp;</i></label>
    <div class="col-sm-8">
        {!! Form::textarea('$name', null, ["class" => "form-control", "placeholder" => trans('$resouce.$name')]) !!}
    </div>
</div>\n
EOD;
        return $string;
    }

    public function makeHidden($name, $resouce, $first = null, $second = null, $third = null)
    {
        $string = <<<EOD
<div class="form-group">
    <label class="col-sm-2 control-label">{{ trans('$resouce.$name') }}<i class="text-red">&nbsp;&nbsp;</i></label>
    <div class="col-sm-8">
        {!! Form::hidden('$name', null, ["class" => "form-control", "placeholder" => trans('$resouce.$name')]) !!}
    </div>
</div>\n
EOD;
        return $string;
    }

    public function makePassword($name, $resouce, $first = null, $second = null, $third = null)
    {
        $string = <<<EOD
<div class="form-group">
    <label class="col-sm-2 control-label">{{ trans('$resouce.$name') }}<i class="text-red">&nbsp;&nbsp;</i></label>
    <div class="col-sm-8">
        {!! Form::password('$name', null, ["class" => "form-control", "placeholder" => trans('$resouce.$name')]) !!}
    </div>
</div>\n
EOD;
        return $string;
    }

    public function makeSelect($name, $resouce, $dropDownValues, $second = null, $third = null)
    {
        $string = <<<EOD
<div class="form-group">
    <label class="col-sm-2 control-label">{{ trans('$resouce.$name') }}<i class="text-red">&nbsp;&nbsp;</i></label>
    <div class="col-sm-8">
        {!! Form::select('$name', $dropDownValues, null, ["class" => "form-control select2", "placeholder" => trans('$resouce.$name')]) !!}
    </div>
</div>\n
EOD;
        return $string;
    }

    public function makeMutiSelect($name, $resouce, $dropDownValues, $selectedValues = null, $third = null)
    {
        $string = <<<EOD
<div class="form-group">
    <label class="col-sm-2 control-label">{{ trans('$resouce.$name') }}<i class="text-red">&nbsp;&nbsp;</i></label>
    <div class="col-sm-8">
        {!! Form::select('$name\[\]', $dropDownValues, is_null($selectedValue) ? null : $selectedValues, ["class" => "form-control select2","mutiple" => "mutiple" "placeholder" => trans('$resouce.$name')]) !!}
    </div>
</div>\n
EOD;
        return $string;
    }

    public function makeRadio($name, $resouce, $value = null, $checked = false, $third = null)
    {
        $string = <<<EOD
<div class="form-group">
    <label class="col-sm-2 control-label">{{ trans('$resouce.$name') }}<i class="text-red">&nbsp;&nbsp;</i></label>
    <div class="col-sm-8">
        {!! Form::radio('$name', '$value', $checked, ["class" => "form-control", "placeholder" => trans('$resouce.$name')]) !!}
    </div>
</div>\n
EOD;
        return $string;
    }

    public function makeCheckBox($name, $resouce, $value = null, $checked = false, $third = null)
    {
        $string = <<<EOD
<div class="form-group">
    <label class="col-sm-2 control-label">{{ trans('$resouce.$name') }}<i class="text-red">&nbsp;&nbsp;</i></label>
    <div class="col-sm-8">
        {!! Form::text('$name', '$value', $checked, ["class" => "form-control", "placeholder" => trans('$resouce.$name')]) !!}
    </div>
</div>\n
EOD;
        return $string;
    }

    public function makeFile($name, $resouce, $first = null, $second = null, $third = null)
    {
        $string = <<<EOD
<div class="form-group">
    <label class="col-sm-2 control-label">{{ trans('$resouce.$name') }}<i class="text-red">&nbsp;&nbsp;</i></label>
    <div class="col-sm-8">
        {!! Form::text('$name', null, ["class" => "form-control", "placeholder" => trans('$resouce.$name')]) !!}
    </div>
</div>\n
EOD;
        return $string;
    }

    public function makeMutiFile($name, $resouce, $first = null, $second = null, $third = null)
    {
        $string = <<<EOD
<div class="form-group">
    <label class="col-sm-2 control-label">{{ trans('$resouce.$name') }}<i class="text-red">&nbsp;&nbsp;</i></label>
    <div class="col-sm-8">
        {!! Form::text('$name'[], ["class" => "form-control", "multiple", "placeholder" => trans('$resouce.$name')]) !!}
    </div>
</div>\n
EOD;
        return $string;
    }

    public function makeDate($name, $resouce, $first = null, $second = null, $third = null)
    {
        $string = <<<EOD
<div class="form-group">
    <label class="col-sm-2 control-label">{{ trans('$resouce.$name') }}<i class="text-red">&nbsp;&nbsp;</i></label>
    <div class="col-sm-8">
        {!! Form::text('$name', null, ["class" => "form-control datepicker", "placeholder" => trans('$resouce.$name')]) !!}
    </div>
</div>\n
EOD;
        return $string;
    }

    public function makeDatetime($name, $resouce, $first = null, $second = null, $third = null)
    {
        $string = <<<EOD
<div class="form-group">
    <label class="col-sm-2 control-label">{{ trans('$resouce.$name') }}<i class="text-red">&nbsp;&nbsp;</i></label>
    <div class="col-sm-8">
        {!! Form::text('$name', null, ["class" => "form-control datetimepicker", "placeholder" => trans('$resouce.$name')]) !!}
    </div>
</div>\n
EOD;
        return $string;
    }
}