<?php
global $lang;
$config->stage->dtable = new stdclass();
$config->stage->dtable->fieldList['id']['type'] = 'id';

$config->stage->dtable->fieldList['name']['type'] = 'name';

$config->stage->dtable->fieldList['type']['type']      = 'status';
$config->stage->dtable->fieldList['type']['statusMap'] = $lang->stage->typeList;

$config->stage->dtable->fieldList['actions']['type'] = 'actions';
$config->stage->dtable->fieldList['actions']['menu'] = array('edit', 'delete');
$config->stage->dtable->fieldList['actions']['list'] = $config->stage->actionList;
