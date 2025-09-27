<?php
namespace zin;

class floatPreNextBtn extends wg
{
    /**
     * @var mixed[]
     */
    protected static $defineProps = array(
        'preLink?:string',
        'nextLink?:string'
    );

    public static function getPageCSS()
    {
        return file_get_contents(__DIR__ . DS . 'css' . DS . 'v1.css');
    }

    public static function getPageJS()
    {
        return file_get_contents(__DIR__ . DS . 'js' . DS . 'v1.js');
    }
    protected function build()
    {
        global $app;
        $preLink  = $this->prop('preLink');
        $nextLink = $this->prop('nextLink');

        return array
        (
            !empty($preLink) ? btn
            (
                setID('preButton'),
                set::url($preLink),
                setClass('float-btn fixed z-10 inverse rounded-full w-12 h-12 center bg-opacity-40 backdrop-blur ring-0'),
                set::icon('angle-left icon-2x text-white'),
                set('data-app', $app->tab)
            ) : null,
            !empty($nextLink) ? btn
            (
                setID('nextButton'),
                set::url($nextLink),
                setClass('float-btn fixed z-10 inverse rounded-full w-12 h-12 center bg-opacity-40 backdrop-blur ring-0'),
                set::icon('angle-right icon-2x text-white'),
                set('data-app', $app->tab)
            ) : null
        );
    }
}
