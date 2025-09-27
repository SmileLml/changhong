<?php
namespace zin;

require_once dirname(__DIR__) . DS . 'formlabel' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'control' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'content' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'input' . DS . 'v1.php';

/**
 * 表单控件组部件。
 * Form control group widget.
 */
class formGroup extends wg
{
    /**
     * @var mixed[]
     */
    protected static $defineProps = array(
        'id?: string',                          // ID。
        'name?: string',                        // 字段名，可能影响到表单提交的域名称，如果是多个值的表单控件，可能需要将名称定义为 `key[]` 的形式。
        'data?: array',                         // 数据属性。
        'label?: string|bool',                  // 标签文本。
        'labelFor?: string',                    // 标签的 for 属性。
        'labelClass?: string',                  // 标签的 class 属性。
        'labelProps?: string',                  // 标签的其它属性。
        'labelWidth?: int|string',              // 标签的宽度。
        'labelHint?: string',                   // 标签的提示文本。
        'labelHintIcon?: string="help"',        // 标签的提示图标。
        'labelHintClass?: string',              // 标签的提示 class 属性。
        'labelHintProps?: array',               // 标签的提示其它属性。
        'labelActions?: array',                 // 标签的操作按钮。
        'labelActionsClass?: string',           // 标签的操作按钮 class 属性。
        'labelActionsProps?: array',            // 标签的操作按钮其它属性。
        'labelControl?: array',                 // 自定义标签控件。
        'checkbox?: bool|array',                // 标签的复选框属性定义。
        'required?:bool|string="auto"',         // 是否必填。
        'requiredFields?: string',              // 必填字段列表，例如 `'product,branch'`。
        'tip?: string',                         // 提示文本。
        'tipClass?: string|array',              // 提示 class 属性。
        'tipProps?: array',                     // 提示其它属性。
        'control?: array|string',               // 表单控件类型或控件属性定义。
        'width?: string',                       // 界面宽度。
        'strong?: bool',                        // 是否加粗。
        'value?: string|array',                 // 值。
        'disabled?: bool',                      // 是否禁用。
        'readonly?: bool',                      // 是否只读。
        'multiple?: bool',                      // 是否多选。
        'hidden?: bool',                        // 是否隐藏。
        'items?: array',                        // 选项列表。
        'placeholder?: string',                 // 占位符。
        'foldable?: bool',                      // 是否可折叠。
        'pinned?: bool',                        // 是否固定。
        'wrapBefore?: bool',                    // 是否在前方换行。
        'wrapAfter?: bool',                     // 是否在后方换行。
        'children?: array|object'               // 内部自定义内容。
    );

    /**
     * @var mixed[]
     */
    protected static $controlExtendProps = array('required', 'name', 'value', 'disabled', 'items', 'placeholder', 'readonly', 'multiple');

    /**
     * @var bool
     */
    protected $isHiddenField = false;

    protected function created()
    {
        $required = $this->prop('required');
        if($required === 'auto')
        {
            $children       = $this->children();
            $requiredFields = $this->prop('requiredFields');
            if($this->hasProp('name')) $required = isFieldRequired($this->prop('name'), $requiredFields);
            else if($children)
            {
                $required = false;
                foreach($children as $child)
                {
                    if($child instanceof node && $child->hasProp('name') && isFieldRequired($child->prop('name'), $requiredFields)) $required = true;
                }
            }
            else                $required = false;
            $this->setProp('required', $required);
        }
    }

    /**
     * @return \zin\node|\zin\set
     */
    protected function buildLabel()
    {
        list($name, $label, $labelFor, $labelClass, $labelProps, $labelHint, $labelHintClass, $labelHintProps, $labelHintIcon, $labelActions, $labelActionsClass, $labelActionsProps, $checkbox, $required, $strong, $labelControl) = $this->prop(array('name', 'label', 'labelFor', 'labelClass', 'labelProps', 'labelHint', 'labelHintClass', 'labelHintProps', 'labelHintIcon', 'labelActions', 'labelActionsClass', 'labelActionsProps', 'checkbox', 'required', 'strong', 'labelControl'));

        if(is_null($label) || $label === false) return setClass('no-label');
        if($labelControl instanceof setting) $labelControl = $labelControl->toArray();

        return new formLabel
        (
            set::className($labelClass, $strong ? 'font-bold' : null),
            set::required($required),
            set::hint($labelHint),
            set::hintIcon($labelHintIcon),
            set::hintClass($labelHintClass),
            set::hintProps($labelHintProps),
            set::actions($labelActions),
            set::actionsClass($labelActionsClass),
            set::actionsProps($labelActionsProps),
            set::checkbox($checkbox),
            set::text($label),
            set('for', is_null($labelFor) ? $name : $labelFor),
            set($labelProps),
            $labelControl ? new content(is_array($labelControl) ? set($labelControl) : $labelControl) : null
        );
    }

    protected function buildControl()
    {
        $control = $this->prop('control');

        if($control instanceof node)                              return $control;
        if(!is_string($control) && is_callable($control, true)) return $control($this->props->toJSON());

        if(is_string($control))                             $control = array('control' => $control);
        elseif($control instanceof item)                    $control = $control->props->toJSON();
        elseif(is_object($control))                         $control = get_object_vars($control);
        elseif(is_null($control) && $this->hasProp('name')) $control = array();

        if(!is_array($control)) return null;

        if($this->hasProp('id') && !isset($control['id'])) $control['id'] = '';
        foreach(static::$controlExtendProps as $controlPropName)
        {
            $controlPropValue = $this->prop($controlPropName);
            if($controlPropValue !== null && !isset($control[$controlPropName])) $control[$controlPropName] = $controlPropValue;
        }

        if(isset($control['control']) && $control['control'] === 'hidden')
        {
            unset($control['control']);
            $this->isHiddenField = true;
            return new input(set::type('hidden'), set($control));
        }

        $controlView = new control(set($control));

        return $controlView;
    }

    protected function buildTip()
    {
        list($tip, $tipClass, $tipProps) = $this->prop(array('tip', 'tipClass', 'tipProps'));
        if(empty($tip)) return null;

        return div
        (
            setClass('form-tip', $tipClass),
            set($tipProps),
            $tip
        );
    }

    protected function build()
    {
        list($name, $labelWidth, $required, $width, $id, $hidden, $foldable, $pinned, $children, $wrapBefore, $wrapAfter, $data) = $this->prop(array('name', 'labelWidth', 'required', 'width', 'id', 'hidden', 'foldable', 'pinned', 'children', 'wrapBefore', 'wrapAfter', 'data'));

        $control = $this->buildControl();
        if($this->isHiddenField) return $control;

        $content = div
        (
            setClass('form-group', array('required' => $required, 'hidden' => $hidden, 'is-foldable' => $foldable, 'is-pinned' => $pinned)),
            zui::width($width),
            setID($id),
            setData('name', $name),
            setData($data),
            setCssVar('form-horz-label-width', $labelWidth),
            set($this->getRestProps()),
            $this->buildLabel(),
            $control,
            $children,
            $this->children(),
            $this->buildTip()
        );

        if($wrapBefore || $wrapAfter) $content = array($content);
        if($wrapBefore)               array_unshift($content, div(setClass('form-grid-wrap'), setData('wrap-before', $name)));
        if($wrapAfter)                array_push($content, div(setClass('form-grid-wrap'), setData('wrap-after', $name)));

        return $content;
    }
}
