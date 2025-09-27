<?php
namespace zin;

requireWg('thinkRadio');

/**
 * 多选题型部件类
 * The thinkCheckbox widget class
 */
class thinkCheckbox extends thinkRadio
{
    /**
     * @var mixed[]
     */
    protected static $defineProps = array
    (
        'minCount?: string',
        'maxCount?: string',
    );

    protected function buildFormItem()
    {
        global $lang, $app;
        $app->loadLang('thinkstep');
        $formItems = parent::buildFormItem();

        list($step, $minCount, $maxCount, $required, $setOption) = $this->prop(array('step', 'minCount', 'maxCount', 'required', 'setOption'));
        if($step)
        {
            $required  = $step->options->required;
            $minCount  = $step->options->minCount ?? null;
            $maxCount  = $step->options->maxCount ?? null;
            $setOption = !empty($step->options->setOption) ? $step->options->setOption : 0;
        }
        $className = 'selectable-rows' . (empty($required) ? ' hidden' : '');

        $formItems[] = formRow
        (
            setClass('gap-4'),
            formGroup(set::label($lang->thinkstep->label->minCount), set::labelClass('required'), setClass($className, 'min-count'), input
            (
                set::placeholder($lang->thinkstep->placeholder->inputContent),
                set::type('number'),
                set::min(1),
                set::name('options[minCount]'),
                set::value($minCount),
                set::disabled($setOption)
            )),
            formGroup
            (
                set::label($lang->thinkstep->label->maxCount),
                set::labelClass('required'),
                setClass($className, 'max-count'),
                input
                (
                    set::placeholder($setOption ? $lang->thinkstep->placeholder->maxCount : $lang->thinkstep->placeholder->inputContent),
                    set::type('number'),
                    set::min(1),
                    set::name('options[maxCount]'),
                    set::value($maxCount),
                    set::disabled($setOption)
                )
            )
        );
        $formItems[] = $this->children();
        return $formItems;
    }
}
