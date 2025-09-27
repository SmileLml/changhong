<?php
/**
 * The view file of product module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      chen.tao <chentao@easycorp.ltd>
 * @package     product
 * @link        https://www.zentao.net
 */

namespace zin;
jsVar('params', "productID={$productID}&branchID={$branchID}&storyType={$storyType}&browseType={$browseType}&moduleID={$moduleID}");
jsVar('projectID', $projectID);
jsVar('storyType', $storyType);
data('activeMenuID', $storyType);

detailHeader
(
    to::title
    (
        entityLabel
        (
            set::level(1),
            set::text($lang->story->report->common)
        )
    )
);

$reports = array();
foreach($lang->story->report->charts as $key => $label) $reports[] = array('text' => $label, 'value' => $key);

function getEcharts($charts, $datas, $chartType)
{
    global $lang;
    $echarts = array();
    foreach($charts as $type => $option)
    {
        $chartData = $datas[$type];
        $echarts[] = tableChart(set::item('chart-' . $type), set::type($chartType), set::title($lang->story->report->charts[$type]), set::datas((array)$chartData));
    }
    return $echarts;
}

$tabItems = array();
unset($lang->report->typeList['default']);
foreach($lang->report->typeList as $type => $typeName)
{
    $tabItems[] = tabPane
    (
        set::key($type),
        set::param($type),
        set::title($typeName),
        set::active($type == $chartType),
        to::prefix(icon($type == 'default' ? 'list-alt' : "chart-{$type}")),
        div(setClass('pb-4 pt-2'), span(setClass('text-gray'), html(str_replace('%tab%', $lang->product->unclosed . $lang->story->common, $lang->report->notice->help)))),
        div(getEcharts($charts, $datas, $type))
    );
}

div
(
    setClass('flex items-start'),
    cell
    (
        set::width('240'),
        setClass('bg-white p-4 mr-5'),
        div(setClass('pb-2'), span(setClass('font-bold'), $lang->story->report->select)),
        div
        (
            setClass('pb-2'),
            control
            (
                set::type('checkList'),
                set::name('charts'),
                set::items($reports)
            )
        ),
        btn
        (
            bind::click('selectAll'),
            $lang->selectAll
        ),
        btn
        (
            setClass('primary ml-4 inited'),
            bind::click('clickInit'),
            $lang->story->report->create
        )
    ),
    cell
    (
        set::flex('1'),
        setClass('bg-white px-4 py-2'),
        setID('report'),
        tabs
        (
            on::show('.tab-pane')->call('handleShowReportTab', jsRaw('event')),
            $tabItems
        )
    )
);
