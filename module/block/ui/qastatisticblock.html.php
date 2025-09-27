<?php
/**
* The qa statistic block view file of block module of ZenTaoPMS.
* @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
* @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
* @author      Yuting Wang <wangyuting@easycorp.ltd>
* @package     block
* @link        https://www.zentao.net
*/

namespace zin;

$active  = isset($params['active']) ? $params['active'] : key($products); // 当前产品 ID。
$product = null;        // 当前产品。 Current active product.
$items   = array();     // 产品导航列表。 Product nav list.

/* 生成左侧菜单项列表。 */
foreach($products as $productItem)
{
    $projectID = isset($params['projectID']) ? $params['projectID'] : 0;
    $params    = helper::safe64Encode("module={$block->module}&projectID={$projectID}&active={$productItem->id}");
    $items[]   = array
    (
        'id'        => $productItem->id,
        'text'      => $productItem->name,
        'url'       => createLink('bug', 'browse', "productID=$productItem->id"),
        'activeUrl' => createLink('block', 'printBlock', "blockID=$block->id&params=$params")
    );
    if($productItem->id == $active) $product = $productItem;
}

/**
 * 构建进度条。
 *
 * @param object $product   产品。
 * @param bool   $longBlock 是否为长块。
 */
$buildProgressBars = function(object $product, bool $longBlock): node
{
    global $lang;
    $progressMax = max($product->addYesterday, $product->addToday, $product->resolvedYesterday, $product->resolvedToday, $product->closedYesterday, $product->closedToday);
    $labels = array();
    $bars   = array();
    $fields = array('addYesterday', 'addToday', 'resolvedYesterday', 'resolvedToday', 'closedYesterday', 'closedToday');
    foreach($fields as $index => $field)
    {
        $isEven = $index % 2 === 0;
        $labels[] = row
        (
            setClass('clip items-center', $isEven ? 'mt-3' : 'text-gray', $longBlock ? 'h-6' : 'h-5'),
            span($lang->block->qastatistic->{$field}),
            span
            (
                setClass('ml-1.5 inline-block text-left', $isEven ? 'font-bold' : ''),
                setStyle('min-width', '1.5em'),
                $product->{$field}
            )
        );
        $bars[] = row
        (
            setClass('items-center ml-1 border-l', $isEven ? 'mt-3' : '', $longBlock ? 'h-6' : 'h-5'),
            progressBar
            (
                setClass('progress flex-auto'),
                set::height(8),
                set::percent(($progressMax ? $product->{$field} / $progressMax : 0) * 100),
                set::color($isEven ? 'var(--color-secondary-200)' : 'var(--color-primary-300)'),
                set::background('rgba(0,0,0,0.02)')
            )
        );
    }

    return row
    (
        cell
        (
            setClass('text-right flex-none'),
            $labels
        ),
        cell
        (
            setClass('flex-auto'),
            $bars
        )
    );
};

/**
 * 构建测试任务列表。
 *
 * @param object $product   产品。
 * @param bool   $longBlock 是否为长块。
 */
$buildTesttasks = function(object $product, bool $longBlock): ?\zin\node
{
    global $lang;
    $unclosedTesttasks = array();
    if(!empty($product->unclosedTesttasks))
    {
        foreach($product->unclosedTesttasks as $waitTesttask)
        {
            $unclosedTesttasks[] = div
            (
                setClass('clip', $longBlock ? 'py-1' : 'py-0.5'),
                hasPriv('testtask', 'cases') ? a(set('href', createLink('testtask', 'cases', "taskID={$waitTesttask->id}")), set('title', $waitTesttask->name), $waitTesttask->name) : span(set('title', $waitTesttask->name), $waitTesttask->name)
            );
            if(count($unclosedTesttasks) >= 6) break;
        }
    }

    return $unclosedTesttasks ? col(setClass('min-w-0 flex-1 gap-1.5 px-3 pt-2 border-l'), div(setClass('font-bold'), $lang->block->qastatistic->unclosedTesttasks), div
    (
        setClass($longBlock ? 'py-2' : 'pt-2'),
        $unclosedTesttasks
    )) : null;
};

$testTasksView = !empty($product) ? $buildTesttasks($product, $longBlock) : null;

statisticBlock
(
    to::titleSuffix
    (
        icon
        (
            setClass('text-light text-sm cursor-pointer'),
            toggle::tooltip
            (
                array
                (
                    'title'     => sprintf($lang->block->tooltips['metricTime'], $metricTime),
                    'placement' => 'bottom',
                    'type'      => 'white',
                    'className' => 'text-dark border border-light leading-5'
                )
            ),
            'help'
        )
    ),
    set::block($block),
    set::active($active),
    set::items($items),
    $product ? div
    (
        setClass('h-full overflow-hidden items-stretch px-2', $longBlock ? 'row py-3' : 'col gap-2 pt-1'),
        center
        (
            setClass('gap-2 px-5 justify-start ', $testTasksView ? 'flex-none' : 'flex-1', $longBlock ? ' py-2' : ''),
            div
            (
                setClass('w-full font-bold'),
                $lang->block->qastatistic->fixBugRate
            ),
            progressCircle
            (
                set::percent($product->fixedBugRate),
                set::size(112),
                set::text(false),
                set::circleWidth(0.06),
                div(span(setClass('text-2xl font-bold'), $product->fixedBugRate), '%')
            ),
            row
            (
                setClass('justify-center items-center gap-4'),
                center
                (
                    div(span(!empty($product->totalBug) ? $product->totalBug : 0)),
                    div
                    (
                        setClass('text-sm text-gray'),
                        $lang->block->bugstatistic->effective
                    )
                ),
                center
                (
                    div(span(!empty($product->fixedBug) ? $product->fixedBug : 0)),
                    div
                    (
                        setClass('text-sm text-gray'),
                        $lang->block->bugstatistic->fixed
                    )
                ),
                center
                (
                    div(span(!empty($product->activatedBug) ? $product->activatedBug : 0)),
                    div
                    (
                        setClass('text-sm text-gray'),
                        $lang->bug->statusList['active']
                    )
                )
            )
        ),
        row
        (
            setClass($testTasksView ? 'flex-auto' : 'flex-1'),
            col
            (
                setClass('flex-1 gap-1.5 px-3 py-2'),
                div(setClass('font-bold'), $lang->block->qastatistic->bugStatistics),
                !empty($product) ? $buildProgressBars($product, $longBlock) : null
            ),
            $testTasksView
        )
    ) : null
);
