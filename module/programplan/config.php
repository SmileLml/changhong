<?php
helper::import(dirname(__FILE__) . 'config/form.php');

$config->programplan->create = new stdclass();
$config->programplan->edit   = new stdclass();
$config->programplan->create->requiredFields = 'name,begin,end';
$config->programplan->edit->requiredFields   = 'name,begin,end';

$config->programplan->datatable = new stdclass();
if(isset($config->setPercent) and $config->setPercent == 1)
{
    $config->programplan->datatable->defaultField = array('id', 'name', 'percent', 'attribute', 'begin', 'end', 'realBegan', 'realEnd', 'actions');
}
else
{
    $config->programplan->datatable->defaultField = array('id', 'name', 'attribute', 'begin', 'end', 'realBegan', 'realEnd', 'actions');
}

$config->programplan->datatable->fieldList['id']['title']    = 'idAB';
$config->programplan->datatable->fieldList['id']['fixed']    = 'left';
$config->programplan->datatable->fieldList['id']['width']    = '70';
$config->programplan->datatable->fieldList['id']['required'] = 'yes';

$config->programplan->datatable->fieldList['name']['title']    = 'name';
$config->programplan->datatable->fieldList['name']['fixed']    = 'left';
$config->programplan->datatable->fieldList['name']['width']    = 'auto';
$config->programplan->datatable->fieldList['name']['required'] = 'yes';

if(isset($config->setPercent) and $config->setPercent == 1)
{
    $config->programplan->datatable->fieldList['percent']['title']    = 'percent';
    $config->programplan->datatable->fieldList['percent']['fixed']    = 'no';
    $config->programplan->datatable->fieldList['percent']['width']    = '100';
    $config->programplan->datatable->fieldList['percent']['required'] = 'no';
}

$config->programplan->datatable->fieldList['attribute']['title']    = 'attribute';
$config->programplan->datatable->fieldList['attribute']['fixed']    = 'no';
$config->programplan->datatable->fieldList['attribute']['width']    = '90';
$config->programplan->datatable->fieldList['attribute']['required'] = 'no';

$config->programplan->datatable->fieldList['begin']['title']    = 'begin';
$config->programplan->datatable->fieldList['begin']['fixed']    = 'no';
$config->programplan->datatable->fieldList['begin']['width']    = '90';
$config->programplan->datatable->fieldList['begin']['required'] = 'no';

$config->programplan->datatable->fieldList['end']['title']    = 'end';
$config->programplan->datatable->fieldList['end']['fixed']    = 'no';
$config->programplan->datatable->fieldList['end']['width']    = '90';
$config->programplan->datatable->fieldList['end']['required'] = 'no';

$config->programplan->datatable->fieldList['realBegan']['title']    = 'realBegan';
$config->programplan->datatable->fieldList['realBegan']['fixed']    = 'no';
$config->programplan->datatable->fieldList['realBegan']['width']    = '90';
$config->programplan->datatable->fieldList['realBegan']['required'] = 'no';

$config->programplan->datatable->fieldList['realEnd']['title']    = 'realEnd';
$config->programplan->datatable->fieldList['realEnd']['fixed']    = 'no';
$config->programplan->datatable->fieldList['realEnd']['width']    = '90';
$config->programplan->datatable->fieldList['realEnd']['required'] = 'no';

$config->programplan->datatable->fieldList['actions']['title']    = 'actions';
$config->programplan->datatable->fieldList['actions']['fixed']    = 'right';
$config->programplan->datatable->fieldList['actions']['width']    = '150';
$config->programplan->datatable->fieldList['actions']['required'] = 'yes';
$config->programplan->datatable->fieldList['actions']['sort']     = 'no';

$config->programplan->list = new stdclass();
$config->programplan->list->customCreateFields          = 'PM,attribute,acl,milestone,realBegan,realEnd';
$config->programplan->list->customAgilePlusCreateFields = 'PM,milestone,acl,desc,attribute';

$config->programplan->custom = new stdclass();
$config->programplan->custom->createFields              = 'PM,attribute,milestone';
$config->programplan->custom->createIpdFields           = 'PM,attribute,milestone';
$config->programplan->custom->createWaterfallFields     = 'PM,attribute,milestone';
$config->programplan->custom->createWaterfallplusFields = 'PM,attribute,milestone';
if(!empty($config->setPercent)) $config->programplan->list->customCreateFields .= ',percent';
if(!empty($config->setCode))
{
    $config->programplan->custom->createFields     .= ',code';
    $config->programplan->list->customCreateFields .= ',code';
}
$config->programplan->custom->defaultFields = 'code,PM,attribute,milestone';

$config->programplan->customAgilePlus = new stdclass();
$config->programplan->customAgilePlus->createFields  = 'code,PM,milestone,acl,desc,attribute';
$config->programplan->customAgilePlus->defaultFields = $config->programplan->customAgilePlus->createFields;

$config->programplan->custom->customGanttFields = 'PM,deadline,status,realBegan,realEnd,progress,taskProgress,estimate,consumed,delay,delayDays';
$config->programplan->ganttCustom = new stdclass();
$config->programplan->ganttCustom->ganttFields = 'PM,deadline';
