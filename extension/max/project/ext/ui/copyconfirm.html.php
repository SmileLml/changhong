<?php
namespace zin;

jsVar('copyProjectID', $copyProjectID);
jsVar('projectModel', $project->model);
jsVar('weekend', $config->execution->weekend);

$title       = in_array($project->model, array('waterfall', 'waterfallplus')) ? $lang->project->stageInfoConfirm : $lang->project->executionInfoConfirm;
$previousUrl = $this->createLink('project', !empty($copyProject->isTpl) ? 'createTemplate' : 'create', "project=$project->model&programID=0&copyProjectID=$copyProjectID&extra=copyType=previous,copyFrom={$copyFrom}&pageType=copy");

/* 批量处理执行数据。 */
foreach($executions as $id => $execution)
{
    $execution->parentAttr = '';
    if(!empty($execution->parent) && isset($executions[$execution->parent]))
    {
        $executions[$id]->parentAttr = $executions[$execution->parent]->attribute;
    }

    $execution->method = zget($lang->execution->typeList, $execution->type);
    if(!empty($copyProject->isTpl))
    {
        $execution->begin  = helper::today();
        $execution->end    = helper::today();
        $execution->days   = 1;
    }
}

$items['id']     = array('name' => 'id', 'label' => $lang->idAB, 'control' => 'hidden', 'hidden' => true);
$items['parent'] = array('name' => 'parent', 'label' => $lang->execution->parent, 'control' => 'hidden', 'hidden' => true);
$items['method'] = array('name' => 'method', 'label' => $lang->execution->method, 'control' => 'static', 'items' => $lang->execution->typeList, 'width' => '40px');
$items['name']   = array('name' => 'name', 'label' => in_array($project->model, array('scrum', 'agileplus')) ? $lang->execution->name : $lang->stage->name, 'control' => 'input', 'required' => true, 'width' => '240px');
$items['PM']     = array('name' => 'PM', 'label' => $lang->project->PM, 'control' => 'picker', 'items' => $users, 'width' => '80px');
$items['begin']  = array('name' => 'begin', 'label' => $lang->execution->begin, 'control' => 'date', 'required' => true, 'width' => '100px', 'hidden' => !empty($copyProject->isTpl));
$items['end']    = array('name' => 'end', 'label' => $lang->execution->end, 'control' => 'date', 'required' => true, 'width' => '100px', 'hidden' => !empty($copyProject->isTpl));

if($project->model == 'scrum' or $project->model == 'agileplus')
{
    $headerTips = !empty($copyProject->isTpl) ? $lang->project->executionInfoTipsAbbr : $lang->project->executionInfoTips;

    if(isset($config->setCode) && $config->setCode == 1) $items['code'] = array('name' => 'code', 'label' => $lang->execution->code, 'control' => 'input', 'required' => strpos(",{$config->execution->create->requiredFields},", ',code,') !== false, 'width' => '80px');
    $items['lifetime'] = array('name' => 'lifetime', 'label' => $lang->execution->type, 'control' => 'picker', 'items' => $lang->execution->lifeTimeList, 'width' => '80px');
    $items['days']     = array('name' => 'days', 'label' => $lang->execution->days, 'control' => 'input', 'width' => '100px', 'hidden' => !empty($copyProject->isTpl));

    if($project->model == 'agileplus') unset($items['method']);

    formBatchPanel
    (
        set::title($title),
        set::mode('edit'),
        on::change('[data-name="begin"]', "computeWorkDays($(e.target).attr('name'))"),
        on::change('[data-name="end"]', "computeWorkDays($(e.target).attr('name'))"),
        on::change('[data-name^="attribute"]', "changeType"),
        set::headingActionsClass('flex-auto justify-between w-11/12'),
        to::headingActions(div(setClass('text-sm text-secondary-500'), $headerTips)),
        set::submitBtnText($lang->project->completeCopy),
        set::actions(array('submit', array('text' => $lang->project->previous, 'class' => 'btn btn-wide', 'url' => $previousUrl))),
        set::data(array_values($executions)),
        set::onRenderRow(jsRaw('renderRowData')),
        set::items(array_values($items))
    );
}
else
{
    jsVar('notCopyStage', $lang->project->notCopyStage);
    jsVar('productPairs', json_encode($oldProductPairs));
    jsVar('chosenProductStage', $lang->project->chosenProductStage);
    jsVar('isTpl', !empty($copyProject->isTpl));

    if(!empty($executionIdList))
    {
        $data = array();
        foreach($executionIdList as $productID => $stageIdList)
        {
            $index = 0;
            foreach($stageIdList as $stageID)
            {
                $execution            = $executions[$stageID];
                $execution->productID = $productID;
                $execution->isFirst   = $index == 0 ? 1 : 0;

                $data[] = $execution;
                $index++;
            }

            if(!empty($copyProject->isTpl)) break;
        }

        $typeList = $project->model == 'ipd' ? $lang->stage->ipdTypeList : $lang->stage->typeList;
        $items['percent']   = array('name' => 'percent', 'label' => $lang->programplan->percent, 'control' => 'input', 'width' => '80px', 'hidden' => empty($config->setPercent));
        $items['attribute'] = array('name' => 'attribute', 'label' => $lang->programplan->attribute, 'control' => 'picker', 'width' => '80px', 'items' => $typeList, 'hidden' => $project->model == 'ipd');
        $items['acl']       = array('name' => 'acl', 'label' => $lang->programplan->acl, 'control' => 'picker', 'width' => '80px', 'items' => $lang->execution->aclList);
        $items['milestone'] = array('name' => 'milestone', 'label' => $lang->programplan->milestone, 'control' => 'radioListInline', 'width' => '80px', 'items' => $lang->programplan->milestoneList);

        $actions = empty($copyProject->isTpl) && count($executionIdList) > 1 && $productID == key($executionIdList) ? '' : array('submit', array('text' => $lang->project->previous, 'class' => 'btn btn-wide', 'url' => $previousUrl));
        formBatchPanel
        (
            set::id('product' . $productID),
            set::title($title),
            set::mode('edit'),
            on::change('[data-name="begin"]', "computeWorkDays($(e.target).attr('name'))"),
            on::change('[data-name="end"]', "computeWorkDays($(e.target).attr('name'))"),
            on::change('[name^="attribute"]', "changeAttribute"),
            set::submitBtnText($lang->project->completeCopy),
            set::onRenderRow(jsRaw('renderRowData')),
            set::actions($actions),
            set::data($data),
            set::items(array_values($items))
        );
    }
    else
    {
        $actions = array('submit', array('text' => $lang->project->previous, 'class' => 'btn btn-wide', 'url' => $previousUrl));
        formBatchPanel
        (
            set::title($title),
            set::mode('edit'),
            set::actions($actions)
        );
    }
}