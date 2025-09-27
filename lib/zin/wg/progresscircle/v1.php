<?php
/**
 * The progressCircle widget class file of zin module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      sunhao<sunhao@easycorp.ltd>
 * @package     zin
 * @link        http://www.zentao.net
 */

namespace zin;

/**
 * 环形进度条（progressCircle）部件类
 * The progressCircle widget class
 */
class progressCircle extends wg
{
    /**
     * Define widget properties.
     *
     * @var    array
     * @access protected
     */
    protected static $defineProps = array
    (
        'percent?: int',           // 百分比。
        'size?: int',              // 大小。
        'circleWidth?: int',       // 环形宽度。
        'circleBg: string="var(--color-surface)"',        // 环形背景色。
        'circleColor: string="var(--color-primary-500)"',     // 环形颜色。
        'text?: string|boolean',   // 文本。
        'textStyle?: string|array',// 文本样式。
        'textX?: int',             // 文本 X 坐标。
        'textY?: int'              // 文本 Y 坐标。
    );

    /**
     * Build widget.
     *
     * @access protected
     * @return mixed
     */
    protected function build()
    {
        $children    = $this->children();
        $class       = $this->prop('class');
        $circleProps = $this->getDefinedProps();
        $hasChildren = !empty($children);

        return div
        (
            set('zui-create', 'progressCircle'),
            setClass(array('hide-before-init transition-opacity', $class, $hasChildren ? 'relative center' : '')),
            setData($circleProps),
            $hasChildren ? div
            (
                setClass('center absolute inset-0 num gap-1'),
                $children
            ) : null
        );
    }
}
