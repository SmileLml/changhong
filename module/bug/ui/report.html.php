<?php
/**
 * The report view file of bug module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang <wangyuting@easycorp.ltd>
 * @package     bug
 * @link        https://www.zentao.net
 */
namespace zin;
jsVar('params', "productID={$productID}&browseType={$browseType}&branchID={$branchID}&moduleID={$moduleID}");

detailHeader
(
    to::title
    (
        entityLabel
        (
            set::level(1),
            set::text($lang->bug->report->common)
        )
    )
);

$reports = array();
foreach($lang->bug->report->charts as $key => $label) $reports[] = array('text' => $label, 'value' => $key);

function getEcharts($charts, $datas, $chartType)
{
    global $lang;
    $echarts = array();
    foreach($charts as $type => $option)
    {
        $chartData = $datas[$type];
        $echarts[] = tableChart
            (
                set::item('chart-' . $type),
                set::type($chartType),
                set::title($lang->bug->report->charts[$type]),
                set::datas((array)$chartData)
            );
    }
    return $echarts;
}

$tabItems = array();
unset($lang->report->typeList['default']);
foreach($lang->report->typeList as $type => $typeName)
{
    $tabItems[] = tabPane
    (
        set::title($typeName),
        set::active($type == $chartType),
        set::param($type),
        set::key($type),
        to::prefix(icon($type == 'default' ? 'list-alt' : "chart-{$type}")),
        div(setClass('pb-4 pt-2'), span(setClass('text-gray'), html(str_replace('%tab%', $lang->bug->unclosed . $lang->bug->common, $lang->report->notice->help)))),
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
        div(setClass('pb-2'), span(setClass('font-bold'), $lang->bug->report->select)),
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
            $lang->bug->report->create
        )
    ),
    cell
    (
        set::flex('1'),
        setClass('bg-white px-4 py-2'),
        setID('report'),
        tabs
        (
            on::show('.tab-pane')->call('handleShowReportTab', jsRaw('event'), jsRaw('args')),
            $tabItems
        )
    )
);

render();
