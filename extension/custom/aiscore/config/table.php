<?php
global $lang, $app;
$config->aiscore->dtable = new stdclass();

$config->aiscore->dtable->fieldList['id']['name']  = 'id';
$config->aiscore->dtable->fieldList['id']['title'] = $lang->idAB;
$config->aiscore->dtable->fieldList['id']['type']  = 'ID';
$config->aiscore->dtable->fieldList['id']['align'] = 'center';
$config->aiscore->dtable->fieldList['id']['group'] = 1;
$config->aiscore->dtable->fieldList['id']['width'] = 40;

$config->aiscore->dtable->fieldList['action']['title']    = $lang->aiscore->action;
$config->aiscore->dtable->fieldList['action']['type']     = 'category';
$config->aiscore->dtable->fieldList['action']['sortType'] = false;
$config->aiscore->dtable->fieldList['action']['group']    = 2;
$config->aiscore->dtable->fieldList['action']['width']    = 100;

$config->aiscore->dtable->fieldList['aiRequestStatus']['title']     = $lang->aiscore->aiRequestStatus;
$config->aiscore->dtable->fieldList['aiRequestStatus']['type']      = 'status';
$config->aiscore->dtable->fieldList['aiRequestStatus']['sortType']  = false;
$config->aiscore->dtable->fieldList['aiRequestStatus']['statusMap'] = $lang->aiscore->aiRequestStatusList;
$config->aiscore->dtable->fieldList['aiRequestStatus']['group']     = 3;
$config->aiscore->dtable->fieldList['aiRequestStatus']['width']     = 60;

$config->aiscore->dtable->fieldList['summary']['name']     = 'summary';
$config->aiscore->dtable->fieldList['summary']['title']    = $lang->aiscore->summary;
$config->aiscore->dtable->fieldList['summary']['type']     = 'number';
$config->aiscore->dtable->fieldList['summary']['sortType'] = false;
$config->aiscore->dtable->fieldList['summary']['group']    = 4;
$config->aiscore->dtable->fieldList['summary']['width']    = 60;

$config->aiscore->dtable->fieldList['createDate']['name']     = 'createDate';
$config->aiscore->dtable->fieldList['createDate']['title']    = $lang->aiscore->createDate;
$config->aiscore->dtable->fieldList['createDate']['type']     = 'datetime';
$config->aiscore->dtable->fieldList['createDate']['sortType'] = false;
$config->aiscore->dtable->fieldList['createDate']['group']    = 5;
$config->aiscore->dtable->fieldList['createDate']['width']    = 100;
