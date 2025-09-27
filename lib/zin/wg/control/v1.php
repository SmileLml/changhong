<?php
namespace zin;

require_once dirname(__DIR__) . DS . 'input' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'textarea' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'editor' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'checkbox' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'checklist' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'radiolist' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'select' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'inputcontrol' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'inputgroup' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'picker' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'datepicker' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'timepicker' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'pripicker' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'severitypicker' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'colorpicker' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'colorinput' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'datetimepicker' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'fileselector' . DS . 'v1.php';

class control extends wg
{
    /**
     * @var mixed[]
     */
    protected static $defineProps = array
    (
        'control?: string',      // 表单输入元素类型，值可以为：static, text, input, password, email, number, date, time, datetime, month, url, search, color, picker, pri, severity, select, checkbox, radio, checkboxList, radioList, checkboxListInline, radioListInline, file, image, textarea, editor
        'type?: string',         // 请使用 control 属性，如果已经指定 control 属性，则此属性作为具体控件的 type 属性。
        'id?: string',           // ID。
        'name: string',          // 控件名称，可能影响到表单提交的域名称，如果是多个值的表单控件，可能需要将名称定义为 `key[]` 的形式。
        'value?: string',        // 控件值。
        'placeholder?: string',  // 占位文本。
        'text?: string',         // 文本。
        'readonly?: bool',       // 是否只读。
        'required?: bool',       // 是否为必填。
        'disabled?: bool',       // 是否禁用。
        'builder?: callable',    // 自定义构建函数。
        'items?: array'          // 选项列表。
    );

    /**
     * @var mixed[]
     */
    protected static $inputTypes = array
    (
        'button', 'datetime-local', 'email', 'file', 'hidden', 'image', 'month', 'number', 'password', 'range', 'reset', 'search', 'submit', 'tel', 'text', 'url', 'week'
    );

    protected function checkErrors()
    {
        if($this->prop('control') == 'upload') trigger_error('[ZIN] The control property value "upload" is deprecated, use "file" instead.', E_USER_WARNING);
    }

    protected function created()
    {
        list($control, $type, $name, $id) = $this->prop(array('control', 'type', 'name', 'id'));

        if(is_null($control))
        {
            $control = $type;
            $type    = null;
            $this->setProp('control', $control);
            $this->setProp('type', null);
        }

        if($control === 'static' && is_null($name))
        {
            $name = '';
            $this->setProp('name', '');
        }

        if(is_null($id) && $name !== null)
        {
            $id = substr($name, -2) == '[]' ? substr($name, 0, - 2) : $name;
            $this->setProp('id', $id);
        }
    }

    /**
     * Build control with static content.
     *
     * @return node
     */
    protected function buildStatic()
    {
        $name = $this->prop('name');
        return div
        (
            set::className('form-control-static'),
            set($this->props->skip(array('type', 'control', 'name', 'value', 'required', 'disabled', 'placeholder', 'items', 'required'))),
            $name ? set('data-name', $name) : null,
            $this->prop('value')
        );
    }

    protected function buildTextarea()
    {
        return new textarea(set($this->props->skip('control')));
    }

    protected function buildInputControl()
    {
        $controlProps = array();
        $allProps     = $this->props->skip(array('control'));
        $propsNames   = array_keys(inputControl::definedPropsList());

        foreach($propsNames as $propName)
        {
            if(!isset($allProps[$propName])) continue;

            $controlProps[$propName] = $allProps[$propName];
            unset($allProps[$propName]);
        }

        return new inputControl
        (
            set($controlProps),
            new input(set($allProps))
        );
    }

    protected function buildInputGroup()
    {
        return new inputGroup(set($this->props->skip(array('control', 'required', 'name'))));
    }

    protected function buildCheckbox()
    {
        if($this->hasProp('items')) return $this->buildCheckList();
        return new checkList
        (
            new checkbox(set($this->props->skip('control')))
        );
    }

    protected function buildCheckList()
    {
        return new checkList
        (
            set($this->props->skip('control'))
        );
    }

    protected function buildRadioList()
    {
        return new radioList
        (
            set($this->props->skip('control'))
        );
    }

    protected function buildCheckListInline()
    {
        return new checkList
        (
            set::inline(true),
            set($this->props->skip('control'))
        );
    }

    protected function buildRadioListInline()
    {
        return new radioList
        (
            set::inline(true),
            set($this->props->skip('control'))
        );
    }

    protected function buildDate()
    {
        return new datePicker(set($this->props->skip('control')));
    }

    protected function buildTime()
    {
        return new timePicker(set($this->props->skip('control')));
    }

    protected function buildPri()
    {
        return new priPicker(set($this->props->skip('control')));
    }

    protected function buildSeverity()
    {
        return new severityPicker(set($this->props->skip('control')));
    }

    protected function buildColor()
    {
        return new colorPicker(set($this->props->skip('control')));
    }

    protected function buildColorInput()
    {
        return new colorInput(set($this->props->skip('control')));
    }

    protected function buildFile()
    {
        return new fileSelector(set($this->props->skip('control')));
    }

    protected function buildFiles()
    {
        return $this->buildFile();
    }

    protected function buildHidden()
    {
        return new input(set::type('hidden'), set($this->props->skip('control')));
    }

    protected function buildText()
    {
        return new input(set::type('text'), set($this->props->skip('control')));
    }

    protected function buildHtml()
    {
        return div
        (
            setClass('article'),
            set($this->getRestProps()),
            html($this->prop('value'))
        );
    }

    protected function buildDropdown()
    {
        return new dropdown($this->prop('text'), set($this->props->skip('control,text,name,widget')));
    }

    protected function build()
    {
        $builder = $this->prop('builder');
        if(is_callable($builder)) return $builder($this->props->skip('builder'), $this->children());

        $control = $this->prop('control');
        if(empty($control)) $control = $this->hasProp('items') ? 'picker' : 'input';

        $methodName = "build{$control}";
        if(method_exists($this, $methodName)) return $this->$methodName();

        $wgName = "\\zin\\$control";
        if(class_exists($wgName)) return new $wgName(set($this->props->skip('control')), $this->children());

        if(!empty($control) && !in_array($control, static::$inputTypes)) return createWg($control, set($this->props->skip('control')), 'input');
        return new input(set::type($control), set($this->props->skip('control')));
    }
}
