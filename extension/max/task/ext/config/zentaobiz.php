<?php
if(!isset($config->task->reportChart)) $config->task->reportChart = new stdclass();

$config->task->reportChart->exportFields = array();
$config->task->reportChart->exportFields['basic']['basic']              = array('taskNum', 'doneNum', 'consumed', 'left');
$config->task->reportChart->exportFields['basic']['doneRate']           = 'image';
$config->task->reportChart->exportFields['basic']['taskRate']           = 'image';
$config->task->reportChart->exportFields['basic']['statusDistribution'] = 'image';
$config->task->reportChart->exportFields['basic']['assignDistribution'] = 'image';
$config->task->reportChart->exportFields['basic']['ownerDistribution']  = 'image';
$config->task->reportChart->exportFields['basic']['moduleDistribution'] = 'image';
$config->task->reportChart->exportFields['basic']['typeDistribution']   = 'image';
$config->task->reportChart->exportFields['basic']['priDistribution']    = 'image';
$config->task->reportChart->exportFields['basic']['reasonDistribution'] = 'image';

$config->task->reportChart->exportFields['progress']['doneRate']  = 'image';
$config->task->reportChart->exportFields['progress']['devRate']   = 'image';
$config->task->reportChart->exportFields['progress']['testRate']  = 'image';
$config->task->reportChart->exportFields['progress']['typeMap']   = 'table';
$config->task->reportChart->exportFields['progress']['statusMap'] = 'table';
$config->task->reportChart->exportFields['progress']['dailyNum']  = 'image';

$config->task->reportChart->exportFields['resource']['basic']             = array('taskNum', 'consumed', 'bugTaskNum', 'bugConsume');
$config->task->reportChart->exportFields['resource']['bugRate']           = 'image';
$config->task->reportChart->exportFields['resource']['bugConsumeRate']    = 'image';
$config->task->reportChart->exportFields['resource']['userEfforts']       = 'image';
$config->task->reportChart->exportFields['resource']['teamEfforts']       = 'table';
$config->task->reportChart->exportFields['resource']['workAssignSummary'] = 'multiTable';
$config->task->reportChart->exportFields['resource']['workSummary']       = 'multiTable';

$config->task->reportChart->tableHeaders['typeMap']     = array('taskType', 'taskCost', 'leftTime', 'progress');
$config->task->reportChart->tableHeaders['statusMap']   = array('taskType', 'status');
$config->task->reportChart->tableHeaders['teamEfforts'] = array('member', 'effort', 'consumed', 'consumedRate');

$config->task->form->create['docs'] = array('type' => 'array', 'required' => false, 'default' => array(), 'filter' => 'join');

$config->task->form->edit['docs']        = array('type' => 'array', 'required' => false, 'default' => array(), 'filter' => 'join');
$config->task->form->edit['oldDocs']     = array('type' => 'array', 'required' => false, 'default' => array());
$config->task->form->edit['docVersions'] = array('type' => 'array', 'required' => false, 'default' => array());
