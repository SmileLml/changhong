<?php
namespace zin;

class thinkCover extends wg
{
    /**
     * @var mixed[]
     */
    protected static $defineProps = array(
        'item: object',      // 模型信息
        'actionUrl: string', // 开始按钮链接
    );

    public static function getPageCSS()
    {
        return file_get_contents(__DIR__ . DS . 'css' . DS . 'v1.css');
    }

    protected function buildCoverContentBlock()
    {
        global $lang;
        $item = $this->prop('item');

        return div
        (
            setClass('bg-white px-8 w-full relative pt-10 pb-6'),
            div
            (
                setClass('flex items-center w-full pt-10 pb-10'),
                div
                (
                    setClass('w-3/5 px-4'),
                    div
                    (
                        setClass('text-2xl'),
                        $item->name
                    ),
                    div
                    (
                        setClass('mb-4 text-lg'),
                        setStyle(array('margin-top' => '-18px')),
                        section
                        (
                            setClass('break-words'),
                            set::content($item->desc),
                            set::useHtml(true)
                        )
                    ),
                    div
                    (
                        setClass('text-md text-black leading-6.5'),
                        $lang->thinkwizard->expect,
                        $item->duration,
                        $lang->thinkwizard->minute
                    )
                ),
                div
                (
                    setClass('w-2/5 pr-4'),
                    img
                    (
                        set::src($item->thumbnail)
                    )
                )
            )
        );
    }

    protected function buildAction()
    {
        global $lang;
        $actionUrl = $this->prop('actionUrl');

        return div
        (
            setClass('py-3 fixed bottom-0 flex justify-center bg-white toolbar-btn border-t border-gray-100'),
            a
            (
                setClass('btn primary px-8 py-2'),
                set::href($actionUrl),
                $lang->thinkwizard->start
            )
        );
    }

    protected function build()
    {

        return array
        (
            $this->buildCoverContentBlock(),
            $this->buildAction()
        );
    }
}
