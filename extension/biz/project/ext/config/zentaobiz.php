<?php
$config->project->form->create['charter']       = array('type' => 'int',    'required' => false, 'default' => 0);
$config->project->form->create['workflowGroup'] = array('type' => 'int',    'control' => 'select', 'required' => false, 'default' => '0', 'options' => array());
$config->project->form->create['linkType']      = array('type' => 'string', 'control' => 'hidden', 'default' => 'plan');

$config->project->form->edit['charter']       = array('type' => 'int',    'required' => false, 'default' => 0);
$config->project->form->edit['workflowGroup'] = array('type' => 'int',    'control' => 'select', 'required' => false, 'default' => '0', 'options' => array());
$config->project->form->edit['linkType']      = array('type' => 'string', 'control' => 'hidden', 'default' => 'plan');

if(!isset($config->project->reportChart)) $config->project->reportChart = new stdclass();
$config->project->reportChart->exportFields = array();
$config->project->reportChart->exportFields['execution']['basic']['basic']         = array('total', 'doingNum', 'closedNum', 'delayNum');
$config->project->reportChart->exportFields['execution']['basic']['closedRate']    = 'image';
$config->project->reportChart->exportFields['execution']['basic']['delayRate']     = 'image';
$config->project->reportChart->exportFields['execution']['basic']['statusMap']     = 'image';
$config->project->reportChart->exportFields['execution']['basic']['doingSummary']  = 'table';
$config->project->reportChart->exportFields['execution']['basic']['closedSummary'] = 'table';

$config->project->reportChart->tableHeaders['doingSummary']  = array('name', 'storyNum', 'taskNum', 'undoneTask', 'undoneStory', 'left', 'consumed', 'progress');
$config->project->reportChart->tableHeaders['closedSummary'] = array('name', 'devRate', 'passRate', 'storyDoneRate', 'devDoneRate', 'testDoneRate', 'testDensity');
