<?php
global $app;
$app->loadLang('story');
$config->product->search['module']             = 'story';
$config->product->search['fields']['title']    = $lang->story->name;
$config->product->search['fields']['id']       = $lang->story->id;
$config->product->search['fields']['keywords'] = $lang->story->keywords;
$config->product->search['fields']['status']   = $lang->story->status;
$config->product->search['fields']['pri']      = $lang->story->pri;
$config->product->search['fields']['module']   = $lang->story->module;
if($config->edition == 'ipd') $config->product->search['fields']['roadmap']  = $lang->story->roadmap;

$config->product->search['fields']['stage']    = $lang->story->stage;
$config->product->search['fields']['product']  = $lang->story->product;
$config->product->search['fields']['branch']   = '';
$config->product->search['fields']['grade']    = $lang->story->grade;
$config->product->search['fields']['plan']     = $lang->story->plan;
$config->product->search['fields']['estimate'] = $lang->story->estimate;

$config->product->search['fields']['source']     = $lang->story->source;
$config->product->search['fields']['sourceNote'] = $lang->story->sourceNote;
$config->product->search['fields']['fromBug']    = $lang->story->fromBug;
$config->product->search['fields']['category']   = $lang->story->category;

$config->product->search['fields']['openedBy']     = $lang->story->openedBy;
$config->product->search['fields']['reviewedBy']   = $lang->story->reviewedBy;
$config->product->search['fields']['result']       = $lang->story->reviewResultAB;
$config->product->search['fields']['assignedTo']   = $lang->story->assignedTo;
$config->product->search['fields']['closedBy']     = $lang->story->closedBy;
$config->product->search['fields']['lastEditedBy'] = $lang->story->lastEditedBy;

$config->product->search['fields']['mailto']       = $lang->story->mailto;

$config->product->search['fields']['closedReason'] = $lang->story->closedReason;
$config->product->search['fields']['version']      = $lang->story->version;

$config->product->search['fields']['openedDate']     = $lang->story->openedDate;
$config->product->search['fields']['reviewedDate']   = $lang->story->reviewedDate;
$config->product->search['fields']['assignedDate']   = $lang->story->assignedDate;
$config->product->search['fields']['closedDate']     = $lang->story->closedDate;
$config->product->search['fields']['lastEditedDate'] = $lang->story->lastEditedDate;
$config->product->search['fields']['activatedDate']  = $lang->story->activatedDate;

$config->product->search['params']['title']          = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->product->search['params']['keywords']       = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->product->search['params']['status']         = array('operator' => '=',       'control' => 'select', 'values' => $lang->story->statusList);
$config->product->search['params']['stage']          = array('operator' => '=',       'control' => 'select', 'values' => $lang->story->stageList);
$config->product->search['params']['pri']            = array('operator' => '=',       'control' => 'select', 'values' => $lang->story->priList);

$config->product->search['params']['product']        = array('operator' => '=',       'control' => 'select', 'values' => '');
$config->product->search['params']['branch']         = array('operator' => '=',       'control' => 'select', 'values' => '');
$config->product->search['params']['grade']          = array('operator' => '=',       'control' => 'select', 'values' => '');
$config->product->search['params']['module']         = array('operator' => 'belong',  'control' => 'select', 'values' => '');
$config->product->search['params']['roadmap']        = array('operator' => '=',       'control' => 'select', 'values' => '');
$config->product->search['params']['plan']           = array('operator' => '=',       'control' => 'select', 'values' => '');
$config->product->search['params']['estimate']       = array('operator' => '=',       'control' => 'input',  'values' => '');

$config->product->search['params']['source']         = array('operator' => '=',       'control' => 'select', 'values' => $lang->story->sourceList);
$config->product->search['params']['sourceNote']     = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->product->search['params']['fromBug']        = array('operator' => '=',       'control' => 'input',  'values' => '');
$config->product->search['params']['category']       = array('operator' => '=',       'control' => 'select', 'values' => $lang->story->categoryList);

$config->product->search['params']['openedBy']       = array('operator' => '=',       'control' => 'select', 'values' => 'users');
$config->product->search['params']['reviewedBy']     = array('operator' => 'include', 'control' => 'select', 'values' => 'users');
$config->product->search['params']['result']         = array('operator' => '=',       'control' => 'select', 'values' => $lang->story->reviewResultList);
$config->product->search['params']['assignedTo']     = array('operator' => '=',       'control' => 'select', 'values' => 'users');
$config->product->search['params']['closedBy']       = array('operator' => '=',       'control' => 'select', 'values' => 'users');
$config->product->search['params']['lastEditedBy']   = array('operator' => '=',       'control' => 'select', 'values' => 'users');

$config->product->search['params']['mailto']         = array('operator' => 'include', 'control' => 'select', 'values' => 'users');

$config->product->search['params']['closedReason']   = array('operator' => '=',       'control' => 'select', 'values' => $lang->story->reasonList);
$config->product->search['params']['version']        = array('operator' => '>=',      'control' => 'input',  'values' => '');

$config->product->search['params']['openedDate']     = array('operator' => '=', 'control' => 'date',  'values' => '');
$config->product->search['params']['reviewedDate']   = array('operator' => '=', 'control' => 'date',  'values' => '');
$config->product->search['params']['assignedDate']   = array('operator' => '=', 'control' => 'date',  'values' => '');
$config->product->search['params']['closedDate']     = array('operator' => '=', 'control' => 'date',  'values' => '');
$config->product->search['params']['lastEditedDate'] = array('operator' => '=', 'control' => 'date',  'values' => '');
$config->product->search['params']['activatedDate']  = array('operator' => '=', 'control' => 'date',  'values' => '');

$app->loadLang('product');
if(!isset($config->product->all)) $config->product->all = new stdclass();
$config->product->all->search['module']                = 'product';
$config->product->all->search['fields']['name']        = $lang->product->name;
$config->product->all->search['fields']['QD']          = $lang->product->QD;
$config->product->all->search['fields']['reviewer']    = $lang->product->reviewer;
$config->product->all->search['fields']['PO']          = $lang->product->PO;
$config->product->all->search['fields']['RD']          = $lang->product->RD;
$config->product->all->search['fields']['desc']        = $lang->product->desc;
$config->product->all->search['fields']['type']        = $lang->product->type;
$config->product->all->search['fields']['id']          = $lang->productCommon . $lang->product->id;
$config->product->all->search['fields']['code']        = $lang->product->code;
$config->product->all->search['fields']['program']     = $lang->product->program;
$config->product->all->search['fields']['line']        = $lang->product->line;
$config->product->all->search['fields']['createdDate'] = $lang->product->createdDate;
$config->product->all->search['fields']['createdBy']   = $lang->product->createdBy;

$config->product->all->search['params']['name']        = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->product->all->search['params']['PO']          = array('operator' => '=',       'control' => 'select', 'values' => 'users');
$config->product->all->search['params']['QD']          = array('operator' => '=',       'control' => 'select', 'values' => 'users');
$config->product->all->search['params']['RD']          = array('operator' => '=',       'control' => 'select', 'values' => 'users');
$config->product->all->search['params']['reviewer']    = array('operator' => 'include', 'control' => 'select', 'values' => 'users');
$config->product->all->search['params']['desc']        = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->product->all->search['params']['type']        = array('operator' => '=',       'control' => 'select', 'values' => $lang->product->typeList);
$config->product->all->search['params']['id']          = array('operator' => '=',       'control' => 'input',  'values' => '');
$config->product->all->search['params']['code']        = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->product->all->search['params']['program']     = array('operator' => '=', 'control' => 'select', 'values' => '');
$config->product->all->search['params']['line']        = array('operator' => '=', 'control' => 'select', 'values' => '');
$config->product->all->search['params']['createdBy']   = array('operator' => '=',       'control' => 'select', 'values' => 'users');
$config->product->all->search['params']['createdDate'] = array('operator' => '=',       'control' => 'date',  'values' => '');

if(empty($config->setCode))
{
    unset($config->product->all->search['fields']['code']);
    unset($config->product->all->search['params']['code']);
}

if($config->systemMode != 'ALM' && $config->systemMode != 'PLM')
{
    unset($config->product->all->search['fields']['program'], $config->product->all->search['fields']['line']);
    unset($config->product->all->search['params']['program'], $config->product->all->search['params']['line']);
}
