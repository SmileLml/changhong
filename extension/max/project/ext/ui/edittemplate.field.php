<?php
namespace zin;

global $lang, $app, $config;

$hasCode        = !empty($config->setCode);
$project        = data('project');
$workflowGroups = data('workflowGroups');
$currency       = $config->project->defaultCurrency;

$fields = defineFieldList('project.edittemplate', 'project');

$fields->field('hasProduct')
    ->label($lang->project->category)
    ->control('checkBtnGroup')
    ->disabled(true)
    ->value(data('project.hasProduct'))
    ->items($lang->project->projectTypeList);

$fields->field('workflowGroup')
    ->label($lang->project->workflowGroup)
    ->control('picker')
    ->disabled(true)
    ->value(data('project.workflowGroup'))
    ->items($workflowGroups);

$fields->field('name')
    ->required(true)
    ->control('input')
    ->value(data('project.name'));

if($hasCode)
{
    $fields->field('code')
        ->control('input')
        ->value(data('project.code'));
}

$budgetFuture = data('project.budget') !== null && !data('project.budget');
$budgetItemList = array();
$budgetUnitList = data('budgetUnitList') ? data('budgetUnitList') : array();
foreach($budgetUnitList as $key => $value)
{
    $budgetItemList[] = array('text' => $value, 'value' => $key, 'url' => "javascript:toggleBudgetUnit('{$key}')");
}
$fields->field('budget')
    ->label($lang->project->budget)
    ->control('inputControl', array('control' => 'input', 'name' => 'budget', 'prefix' => array('control' => 'dropdown', 'name' => 'budgetUnit', 'items' => $budgetItemList, 'widget' => true, 'text' => zget($lang->project->currencySymbol, data('project.budgetUnit') ? data('project.budgetUnit') : $currency), 'className' => 'ghost'), 'prefixWidth' => 34, 'disabled' => $budgetFuture))
    ->tip(' ')
    ->tipProps(array('id' => 'budgetTip'))
    ->value(data('project.budget'))
    ->tipClass('text-danger');

$fields->field('budget')->checkbox(array('text' => $lang->project->future, 'name' => 'future', 'checked' => $budgetFuture));
$fields->field('budgetUnit')->control('hidden')->value($currency);

$fields->field('PM')
    ->control('picker')
    ->items(data('users'))
    ->value(data('project.PM'));

$fields->field('desc')
    ->width('full')
    ->value(data('project.desc'))
    ->control('editor');

$fields->field('acl')->control(array('control' => 'aclBox', 'aclItems' => $lang->project->aclList, 'aclValue' => data('project.acl'), 'whitelistLabel' => $lang->project->whitelist, 'userValue' => data('project.whitelist')))->value(data('project.acl'));

$fields->field('auth')
    ->width('full')
    ->control('radioList')
    ->items($lang->project->authList)
    ->value(data('project.auth'));

$storyTypeList = array();
foreach($lang->story->typeList as $key => $text)
{
    $disabled = $key == 'story' ? true : false;
    $storyTypeList[] = array('text' => $text, 'value' => $key, 'disabled' => $disabled);
}

$fields->field('taskDateLimit')
    ->width('full')
    ->control(array('control' => 'radioList', 'items' => $lang->project->taskDateLimitList, 'name' => 'taskDateLimit'))
    ->value(data('project.taskDateLimit'));

$fields->field('storyType')
    ->width('full')
    ->control(array('control' => 'checkBox', 'items' => $storyTypeList, 'name' => 'storyType[]'))
    ->value(data('project.storyType'));
