<?php
namespace zin;

require_once dirname(__DIR__) . DS . 'btn' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'input' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'inputcontrol' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'picker' . DS . 'v1.php';

class inputGroup extends wg
{
    /**
     * @var mixed[]
     */
    protected static $defineProps = array(
        'items?:array',
        'seg?:bool'
    );

    public function onBuildItem($item)
    {
        if(is_string($item)) $item = new item(set(array('control' => 'addon', 'text' => $item)));
        elseif(is_array($item)) $item = new item(set($item));
        elseif($item instanceof node || is_null($item)) return $item;

        list($control, $type) = $item->prop(array('control', 'type'));
        if(is_array($control))
        {
            $controlProps = $control;
            if(isset($control['control']))
            {
                $control = $control['control'];
                unset($controlProps['control']);
                $item->setProp('control', $control);
            }
            $item->setProp($controlProps);
        }
        if(is_null($control) && !is_null($type))
        {
            $control = $type;
            $type    = null;
            $item->setProp('control', $control);
            $item->setProp('type', null);
        }

        if($control === 'addon')      return h::span(setClass('input-group-addon'), set($item->props->skip('control,text')), $item->prop('text'));
        if($control === 'span')       return h::span(setClass('px-2 h-8 flex items-center'), set($item->props->skip('control,text')), $item->prop('text'));
        if($control === 'btn')        return new btn(set($item->props->skip('control')));
        if($control === 'picker')     return new picker(set($item->props->skip('control')));
        if($control === 'datePicker') return new datePicker(set($item->props->skip('control')));

        if(!empty($control)) return createWg($control, set($item->props->skip('control')), 'input');
        return new input(set::type($control), set($item->props->skip('control')));
    }

    protected function build()
    {
        list($items, $seg) = $this->prop(['items', 'seg']);
        $children = $this->children();

        return div
        (
            setClass('input-group', $seg ? 'input-group-segment' : null),
            set($this->getRestProps()),
            is_array($items) ? array_map(array($this, 'onBuildItem'), $items) : null,
            is_array($children) ? array_map(array($this, 'onBuildItem'), $children) : null
        );
    }
}
