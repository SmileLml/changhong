<?php
namespace zin;

class sidebar extends wg
{
    /**
     * @var mixed[]
     */
    protected static $defineProps = array(
        'side?:string="left"',
        'width?:string|number=160',
        'maxWidth?:string|number=360',
        'minWidth?:string|number=160',
        'showToggle?:bool=true',
        'parent?:string',
        'preserve?:string',
        'dragToResize?:bool=true',
        'toggleBtn?:bool=true',
        'onToggle?:function'
    );

    protected function checkErrors()
    {
        list($maxWidth, $minWidth, $width) = $this->prop(array('maxWidth', 'minWidth', 'width'));
        if(is_numeric($width))
        {
            if(is_numeric($maxWidth) && $width > $maxWidth) trigger_error('[ZIN] The property "width" value ' . $width . ' must be less than or equal to the property "maxWidth" value ' . $maxWidth . ' in sidebar().', E_USER_WARNING);
            if(is_numeric($minWidth) && $width < $minWidth) trigger_error('[ZIN] The property "width" value ' . $width . ' must be greater than or equal to the property "minWidth" value ' . $minWidth . ' in sidebar().', E_USER_WARNING);
        }
    }

    protected function build()
    {
        list($side, $showToggle, $width, $preserve, $parent, $maxWidth, $minWidth, $dragToResize, $onToggle, $toggleBtn) = $this->prop(array('side', 'showToggle', 'width', 'preserve', 'parent', 'maxWidth', 'minWidth', 'dragToResize', 'onToggle', 'toggleBtn'));
        if($preserve === null)
        {
            global $app;
            $preserve = $app->rawModule . '-' . $app->rawMethod;
        }
        return div
        (
            setClass('sidebar'),
            zui::create('sidebar', array('side' => $side, 'toggleBtn' => $showToggle, 'preserve' => $preserve, 'parent' => $parent, 'maxWidth' => $maxWidth, 'minWidth' => $minWidth, 'width' => $width, 'dragToResize' => $dragToResize, 'onToggle' => $onToggle, 'toggleBtn' => $toggleBtn)),
            set($this->getRestProps()),
            $this->children()
        );
    }
}
