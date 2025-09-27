<?php
/**
 * The tree view file of execution module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao <caoyanyi@easycorp.ltd>
 * @package     execution
 * @link        https://www.zentao.net
 */
namespace zin;

include 'header.html.php';

/* zin: Define the feature bar on main menu. */
featureBar
(
    set::current('all'),
    set::linkParams("executionID={$execution->id}&status={key}"),
    checkbox
    (
        set::rootClass('ml-2'),
        set::name('showStory'),
        set::checked($this->cookie->showStory),
        set::text($lang->execution->treeLevel['story']),
        on::change('changeDisplay')
    )
);

/* zin: Define the toolbar on main menu. */
if(!isset($browseType)) $browseType = 'all';
if(!isset($orderBy))    $orderBy = '';

$canImportTask = hasPriv('task', 'importTask');
if(common::canModify('execution', $execution))
{
    if($canImportTask && $execution->multiple) $importTaskItem = array('text' => $lang->execution->importTask, 'url' => $this->createLink('execution', 'importTask', "execution={$execution->id}"));
}
toolbar
(
    hasPriv('task', 'report') ? item(set(array
    (
        'class' => 'btn ghost',
        'icon'  => 'bar-chart',
        'text'  => $lang->task->report->common,
        'url'   => createLink('task', 'report', "execution={$executionID}&browseType={$browseType}")
    ))) : null,
    hasPriv('task', 'export') ? item(set(array
    (
        'class'       => 'btn ghost',
        'icon'        => 'export',
        'text'        => $lang->export,
        'url'         => createLink('task', 'export', "execution={$executionID}&orderBy={$orderBy}&type=tree"),
        'data-toggle' => 'modal'
    ))) : null,
    !empty($importTaskItem) ? dropdown(
        btn(
            setClass('btn ghost'),
            set::icon('import'),
            $lang->import
        ),
        set::items(array_filter(array($importTaskItem))),
        set::placement('bottom-end')
    ) : null,
    hasPriv('task', 'create') ? item(set(array
    (
        'icon' => 'plus',
        'text' => $lang->task->create,
        'class' => 'primary create-execution-btn',
        'url'   => createLink('task', 'create', "execution={$executionID}")
    ))) : null
);

$noData = null;
if(empty($tree))
{

    $noData = div(
        setClass('table-empty-tip h-60 flex items-center justify-center'),
        span
        (
            setClass('text-gray'),
            $lang->task->noTask
        ),
        common::hasPriv('task', 'create', $execution) ? btn
        (
            set::text($lang->task->create),
            set::icon('plus'),
            set::url(createLink('task', 'create', "execution={$executionID}" . (isset($moduleID) ? "&storyID=&moduleID={$moduleID}" : ''))),
            setClass('primary ml-2')
        ) : null
    );
}

div
(
    setClass('flex flex-nowrap'),
    panel
    (
        setClass('flex-auto'),
        $noData ? $noData : tree
        (
            set::id('taskTree'),
            set::items($tree),
            set::canSplit(false),
            set::hover(true),
            set::defaultNestedShow(true),
            set::onClickItem(jsRaw('loadObject'))
        )
    ),
    div
    (
        setID('detailBlock'),
        setClass('w-96 ml-4 hidden')
    )
);
