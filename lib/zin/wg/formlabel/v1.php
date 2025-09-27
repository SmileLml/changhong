<?php
namespace zin;

class formLabel extends wg
{
    /**
     * @var mixed[]
     */
    protected static $defineProps = array(
        'text?: string',
        'required?: bool',
        'for?: string',
        'hint?: string',
        'hintIcon?: string',
        'hintClass?: string',
        'hintProps?: array',
        'actions?: array',
        'actionsClass?: string',
        'actionsProps?: array',
        'checkbox?: bool|array'
    );

    protected function build()
    {
        list($text, $required, $for, $hint, $hintClass, $hintProps, $hintIcon, $actions, $actionsClass, $actionsProps, $checkbox) = $this->prop(array('text', 'required', 'for', 'hint', 'hintClass', 'hintProps', 'hintIcon', 'actions', 'actionsClass', 'actionsProps', 'checkbox'));

        if(!empty($hint))
        {
            $hint = btn
            (
                set::size('sm'),
                set::icon(is_null($hintIcon) ? 'help' : $hintIcon),
                setClass('ghost form-label-hint text-gray-300', $hintClass),
                toggle::tooltip(array('title' => $hint, 'className' => 'text-gray border border-gray-300', 'type' => 'white', 'placement' => 'right')),
                set($hintProps)
            );
        }

        if(is_array($checkbox)) $checkbox = checkbox(set($checkbox));

        if(is_array($actions))
        {
            $actions = toolbar
            (
                setClass('form-label-actions size-sm', $actionsClass),
                set::btnClass('primary-ghost'),
                set::items($actions),
                set($actionsProps)
            );
        }

        return h::label
        (
            setClass('form-label', $required ? 'required' : null),
            set('for', $for),
            set($this->getRestProps()),
            span(setClass('text'), $text),
            $this->children(),
            $hint,
            $checkbox,
            $actions
        );
    }
}
