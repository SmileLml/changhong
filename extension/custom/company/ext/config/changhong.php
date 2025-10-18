<?php

global $lang;
$config->company->user->dtable->fieldList['scoreStatistic']['name']     = 'scoreStatistic';
$config->company->user->dtable->fieldList['scoreStatistic']['title']    = $lang->user->scoreStatistic;
$config->company->user->dtable->fieldList['scoreStatistic']['type']     = 'category';
$config->company->user->dtable->fieldList['scoreStatistic']['map']      = $lang->user->scoreStatisticList;
$config->company->user->dtable->fieldList['scoreStatistic']['sortType'] = true;
$config->company->user->dtable->fieldList['scoreStatistic']['width']    = '120';
$config->company->user->dtable->fieldList['scoreStatistic']['group']    = '2';

$config->company->browse->search['fields']['scoreStatistic'] = $lang->user->scoreStatistic;

$config->company->browse->search['params']['scoreStatistic'] = array('operator' => '=', 'control' => 'select', 'values' => $lang->user->scoreStatisticList);
