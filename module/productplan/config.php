<?php
$config->productplan = new stdclass();
$config->productplan->create = new stdclass();
$config->productplan->edit   = new stdclass();
$config->productplan->create->requiredFields = 'title';
$config->productplan->edit->requiredFields   = 'title';

$config->productplan->editor = new stdclass();
$config->productplan->editor->create = array('id' => 'desc', 'tools' => 'simpleTools');
$config->productplan->editor->edit   = array('id' => 'desc', 'tools' => 'simpleTools');
$config->productplan->editor->start  = array('id' => 'desc', 'tools' => 'simpleTools');
$config->productplan->editor->close  = array('id' => 'comment', 'tools' => 'simpleTools');
$config->productplan->editor->view   = array('id' => 'lastComment', 'tools' => 'simpleTools');

$config->productplan->laneColorList = array('#32C5FF', '#006AF1', '#9D28B2', '#FF8F26', '#FFC20E', '#00A78E', '#7FBB00', '#424BAC', '#C0E9FF', '#EC2761');

$config->productplan->future = '2030-01-01';

global $app, $lang;
$config->productplan->defaultFields['story']     = array('id', 'title', 'pri', 'branch', 'module', 'status', 'openedBy', 'estimate', 'stage', 'assignedTo', 'actions');
$config->productplan->defaultFields['bug']       = array('id', 'title', 'pri', 'status', 'openedBy', 'assignedTo', 'actions');
$config->productplan->defaultFields['linkStory'] = array('id', 'pri', 'plan', 'module', 'title', 'openedBy', 'assignedTo', 'estimate', 'status', 'stage');
$config->productplan->defaultFields['linkBug']   = array('id', 'pri', 'title', 'openedBy', 'assignedTo', 'status');

$config->productplan->actionList['start']['icon']         = 'play';
$config->productplan->actionList['start']['hint']         = $lang->productplan->start;
$config->productplan->actionList['start']['text']         = $lang->productplan->start;
$config->productplan->actionList['start']['url']          = helper::createLink('productplan', 'start', 'planID={id}');
$config->productplan->actionList['start']['data-confirm'] = $lang->productplan->confirmStart;
$config->productplan->actionList['start']['className']    = 'ajax-submit';

$config->productplan->actionList['finish']['icon']         = 'checked';
$config->productplan->actionList['finish']['hint']         = $lang->productplan->finish;
$config->productplan->actionList['finish']['text']         = $lang->productplan->finish;
$config->productplan->actionList['finish']['url']          = helper::createLink('productplan', 'finish', 'planID={id}');
$config->productplan->actionList['finish']['data-confirm'] = $lang->productplan->confirmFinish;
$config->productplan->actionList['finish']['innerClass']   = 'ajax-submit';

$config->productplan->actionList['close']['icon']        = 'off';
$config->productplan->actionList['close']['hint']        = $lang->productplan->close;
$config->productplan->actionList['close']['text']        = $lang->productplan->close;
$config->productplan->actionList['close']['url']         = helper::createLink('productplan', 'close', 'planID={id}');
$config->productplan->actionList['close']['data-toggle'] = 'modal';

$config->productplan->actionList['activate']['icon']         = 'magic';
$config->productplan->actionList['activate']['hint']         = $lang->productplan->activate;
$config->productplan->actionList['activate']['text']         = $lang->productplan->activate;
$config->productplan->actionList['activate']['url']          = helper::createLink('productplan', 'activate', 'planID={id}');
$config->productplan->actionList['activate']['data-confirm'] = $lang->productplan->confirmActivate;
$config->productplan->actionList['activate']['innerClass']   = 'ajax-submit';

$config->productplan->actionList['createExecution']['icon']         = 'plus';
$config->productplan->actionList['createExecution']['hint']         = $lang->productplan->createExecution;
$config->productplan->actionList['createExecution']['text']         = $lang->productplan->createExecution;
$config->productplan->actionList['createExecution']['url']          = array('module' => 'execution', 'method' => 'create');
$config->productplan->actionList['createExecution']['notLoadModel'] = true;
if(!$app->rawModule || $app->rawModule != 'projectplan')
{
    $config->productplan->actionList['createExecution']['data-target'] = '#createExecutionModal';
    $config->productplan->actionList['createExecution']['data-toggle'] = 'modal';
}

$config->productplan->actionList['linkStory']['icon']         = 'link';
$config->productplan->actionList['linkStory']['hint']         = $lang->productplan->linkStory;
$config->productplan->actionList['linkStory']['text']         = $lang->productplan->linkStory;
$config->productplan->actionList['linkStory']['notLoadModel'] = true;
if($app->rawModule) $config->productplan->actionList['linkStory']['url']  = array('module' => $app->rawModule, 'method' => 'view', 'params' => 'planID={id}&type=story&orderBy=id_desc&link=true');

$config->productplan->actionList['linkBug']['icon']         = 'bug';
$config->productplan->actionList['linkBug']['hint']         = $lang->productplan->linkBug;
$config->productplan->actionList['linkBug']['text']         = $lang->productplan->linkBug;
$config->productplan->actionList['linkBug']['notLoadModel'] = true;
if($app->rawModule) $config->productplan->actionList['linkBug']['url']  = array('module' => $app->rawModule, 'method' => 'view', 'params' => 'planID={id}&type=bug&orderBy=id_desc&link=true');

$config->productplan->actionList['edit']['icon']         = 'edit';
$config->productplan->actionList['edit']['hint']         = $lang->productplan->edit;
$config->productplan->actionList['edit']['text']         = $lang->productplan->edit;
$config->productplan->actionList['edit']['notLoadModel'] = true;
if($app->rawModule) $config->productplan->actionList['edit']['url']  = array('module' => $app->rawModule, 'method' => 'edit', 'params' => 'planID={id}');

$config->productplan->actionList['create']['icon']         = 'split';
$config->productplan->actionList['create']['hint']         = $lang->productplan->createChildren;
$config->productplan->actionList['create']['text']         = $lang->productplan->createChildren;
$config->productplan->actionList['create']['notLoadModel'] = true;
if($app->rawModule) $config->productplan->actionList['create']['url']  = array('module' => $app->rawModule, 'method' => 'create', 'params' => 'product={product}&branch={branch}&parent={id}');

$config->productplan->actionList['delete']['icon']         = 'trash';
$config->productplan->actionList['delete']['hint']         = $lang->productplan->delete;
$config->productplan->actionList['delete']['text']         = $lang->productplan->delete;
$config->productplan->actionList['delete']['url']          = helper::createLink('productplan', 'delete', 'planID={id}');
$config->productplan->actionList['delete']['data-confirm'] = array('message' => $lang->productplan->confirmDelete, 'icon' => 'icon-exclamation-sign', 'iconClass' => 'warning-pale rounded-full icon-2x');
$config->productplan->actionList['delete']['innerClass']   = 'ajax-submit';

$config->productplan->actionList['unlinkBug']['icon'] = 'unlink';
$config->productplan->actionList['unlinkBug']['hint'] = $lang->productplan->unlinkBug;
$config->productplan->actionList['unlinkBug']['url']  = 'javascript:unlinkObject("bug", "{id}")';

$config->productplan->actionList['unlinkStory']['icon'] = 'unlink';
$config->productplan->actionList['unlinkStory']['hint'] = $lang->productplan->unlinkStory;
$config->productplan->actionList['unlinkStory']['url']  = 'javascript:unlinkObject("story", "{id}")';

$config->productplan->actions = new stdclass();
$config->productplan->actions->view = array();
$config->productplan->actions->view['mainActions']   = array('start', 'finish', 'close', 'activate', 'create');
$config->productplan->actions->view['suffixActions'] = array('edit', 'delete');
