<?php
/**
 * The browsebykanban view file of productplan module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang <wangyuting@easycorp.ltd>
 * @package     productplan
 * @link        https://www.zentao.net
 */
namespace zin;
foreach($kanbanList as $current => $region)
{
    foreach($region['items'] as $index => $group)
    {
        $group['draggable']  = true;
        $group['colWidth']   = 'auto';
        $group['getCol']     = jsRaw('window.getCol');
        $group['getItem']    = jsRaw('window.getItem');
        $group['canDrop']    = jsRaw('window.canDrop');
        $group['onDrop']     = jsRaw('window.onDrop');
        $group['itemProps']  = array('actions' => jsRaw('window.getItemActions'));
        $kanbanList[$current]['items'][$index] = $group;
    }
}

$orderItems = array();
foreach($lang->productplan->orderList as $order => $label)
{
    $orderItems[] = array('text' => $label, 'active' => $orderBy == $order, 'url' => $this->createLink($app->rawModule, 'browse', "productID=$productID&branch=$branchID&browseType=$browseType&queryID=$queryID&orderBy=$order"));
}

$labelItems = array();
if($product->type == 'normal')
{
    $labelItems[] = array('text' => $lang->productplan->all . ' ' . $planCount, 'icon' => 'delay', 'class' => 'statistic-plan');
}
else
{
    $branchItems = array();
    foreach($branches as $key => $value)
    {
        $branchItems[] = array('text' => $value, 'active' => $key == $branchID, 'url' => createLink($app->rawModule, 'browse', "productID={$productID}&branch={$key}"));
    }
    $labelItems[] = array('text' => $branches[$branchID], 'items' => $branchItems, 'type' => 'dropdown', 'icon' => 'delay', 'className' => 'statistic-plan btn btn-caret');
}

!empty($labelItems) ? featureBar
(
    set::items($labelItems)
) : null;

jsVar('plans', $plans);

toolbar
(
    set::className('w-full justify-end'),
    dropdown
    (
        btn(setClass('ghost'), isset($lang->productplan->orderList[$orderBy]) ? $lang->productplan->orderList[$orderBy] : $lang->productplan->orderList['begin_desc']),
        set::items($orderItems)
    ),
    btnGroup
    (
        btn(setClass($viewType == 'list'   ? 'text-primary font-bold shadow-inner bg-canvas' : ''), set::icon('format-list-bulleted'), setData('type', 'list'), setClass('switchButton'), setData('app', $app->tab)),
        btn(setClass($viewType == 'kanban' ? 'text-primary font-bold shadow-inner bg-canvas' : ''), set::icon('kanban'), setData('type', 'kanban'), setClass('switchButton'), setData('app', $app->tab))
    ),
    common::hasPriv('productplan', 'create') ? item
    (
        set
        (
            array
            (
                'text'  => $lang->productplan->create,
                'url'   => $this->createLink($app->rawModule, 'create', "productID=$product->id&branch=$branch"),
                'icon'  => 'plus',
                'class' => 'btn primary'
            )
        )
    ) : null
);

zui::kanbanList
(
    set::key('kanban'),
    set::items($kanbanList),
    set::height('calc(100vh - 120px)')
);

modalTrigger
(
    modal
    (
        setID('createExecutionModal'),
        set::modalProps(array('title' => $lang->productplan->selectProjects)),
        form
        (
            setID('createExecutionForm'),
            setClass('py-4'),
            set::actions
            (
                array
                (
                    array
                    (
                        'text' => !empty($projects) ? $lang->productplan->nextStep : $lang->productplan->enterProjectList,
                        'id'   => !empty($projects) ? 'createExecutionButton' : '',
                        'type' => 'primary',
                        'url'  => !empty($projects) ? '###' : createLink('product', 'project', "status=all&productID={$productID}&branch={$branch}")
                    ),
                    array
                    (
                        'text' => $lang->cancel,
                        'data-dismiss' => 'modal'
                    )
                )
            ),
            formGroup
            (
                set::label($lang->productplan->project),
                picker
                (
                    set::name('project'),
                    set::items($projects),
                    set::required(true),
                    set::disabled(empty($projects))
                )
            ),
            formRow
            (
                !empty($projects) ? setClass('hidden') : null,
                setClass('projectTips'),
                formGroup
                (
                    set::label(''),
                    span
                    (
                        setClass('text-danger'),
                        $lang->productplan->noLinkedProject
                    ),
                    formHidden('planID', '')
                )
            )
        )
    )
);
render();
