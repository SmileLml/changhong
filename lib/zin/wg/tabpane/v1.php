<?php
namespace zin;

class tabPane extends wg
{
    /**
     * @var mixed[]
     */
    protected static $defineProps = array(
        'key?: string',
        'title?: string',
        'active?: bool=false',
        'param?: string'
    );

    /**
     * @var mixed[]
     */
    protected static $defineBlocks = array(
        'prefix'  => array(),
        'suffix'  => array(),
        'divider' => false
    );

    protected function created()
    {
        $key = $this->prop('key');
        if(is_null($key))
        {
            $key = $this->gid;
            $this->setProp('key', $key);
        }
    }

    protected function build()
    {
        $key    = $this->prop('key');
        $active = $this->prop('active');

        return div
        (
            setID($key),
            setClass('tab-pane', $active ? 'active' : null),
            set($this->getRestProps()),
            $this->children()
        );
    }
}
