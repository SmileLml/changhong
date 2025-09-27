<?php
/**
 * The testtask view file of my module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang <wangyuting@easycorp.ltd>
 * @package     my
 * @link        https://www.zentao.net
 */
namespace zin;

include 'header.html.php';

featureBar
(
    set::current($type),
    set::linkParams("mode=testtask&type={key}&param=&orderBy={$orderBy}")
);

foreach($config->my->testtask->dtable->fieldList['actions']['list'] as $actionKey => $action)
{
    if(!isset($action['data-toggle']) && !isset($action['data-confirm'])) $config->my->testtask->dtable->fieldList['actions']['list'][$actionKey]['data-app'] = 'qa';
}

$tasks      = initTableData($tasks, $config->my->testtask->dtable->fieldList, $this->testtask);
$cols       = array_values($config->my->testtask->dtable->fieldList);
$data       = array_values($tasks);
$footerHTML = $app->rawMethod == 'work' ? sprintf($lang->testtask->mySummary, count($tasks), $waitCount, $testingCount, $blockedCount) : sprintf($lang->testtask->pageSummary, count($tasks));
dtable
(
    set::cols($cols),
    set::data($data),
    set::fixedLeftWidth('20%'),
    set::orderBy($orderBy),
    set::sortLink(createLink('my', $app->rawMethod, "mode={$mode}&type={$type}&param={$param}&orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}")),
    set::footer(array(array('html' => $footerHTML), 'flex', 'pager')),
    set::footPager(usePager()),
    set::emptyTip($lang->testtask->noTesttask)
);

render();
