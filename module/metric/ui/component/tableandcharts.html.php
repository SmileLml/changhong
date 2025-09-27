<?php
/**
 * The browse view file of company module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     company
 * @link        https://www.zentao.net
 */
namespace zin;
jsVar('updateTimeTip', $lang->metric->updateTimeTip);

$fnGenerateTableAndCharts = function($metric) use($viewType, $groupHeader, $groupData, $tableWidth, $pagerExtra, $headerGroup, $echartOptions, $chartTypeList, $noDataTip)
{
    return div
    (
        setClass("table-and-chart table-and-chart-{$viewType}" . ($groupData ? '' : ' no-data')),
        $groupData ? div
        (
            setClass('table-side'),
            setStyle(array('flex-basis' => $tableWidth . 'px')),
            div
            (
                dtable
                (
                    setID('ajaxmetric' . $metric->id),
                    $viewType == 'multiple' ? set::height(328) : set::height(jsRaw('window.getTableHeight')),
                    set::rowHeight(32),
                    set::bordered(true),
                    set::cols($groupHeader),
                    set::data(array_values($groupData)),
                    set::footPager(usePager('dtablePager', $pagerExtra)),
                    $headerGroup ? set::plugins(array('header-group')) : null,
                    set::onRenderCell(jsRaw('window.renderDTableCell')),
                    set::loadPartial(true)
                )
            )
        ) : null,
        $echartOptions ? div
        (
            setStyle(array('width' => "calc(100vh - {$tableWidth}px)")),
            setClass('chart-side'),
            div
            (
                setClass('chart-type'),
                picker
                (
                    set::name('chartType'),
                    set::items($chartTypeList),
                    set::value('line'),
                    set::required(true),
                    set::onchange("window.handleChartTypeChange($metric->id, '$viewType')")
                )
            ),
            div
            (
                setClass("chart chart-{$viewType}"),
                echarts
                (
                    set::width('100%'),
                    set::height('100%'),
                    set::xAxis($echartOptions['xAxis']),
                    set::yAxis($echartOptions['yAxis']),
                    set::legend($echartOptions['legend']),
                    set::series($echartOptions['series']),
                    isset($echartOptions['dataZoom']) ? set::dataZoom($echartOptions['dataZoom']) : null,
                    set::grid($echartOptions['grid']),
                    set::tooltip($echartOptions['tooltip'])
                )
            )
        ) : null,
        $groupData ? null : span($noDataTip, setClass('text-md'))
    );
};
