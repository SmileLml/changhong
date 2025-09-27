<?php
$config->story->form->create['docs'] = array('type' => 'array', 'required' => false, 'default' => array(), 'filter' => 'join');

$config->story->form->edit['docs']        = array('type' => 'array', 'required' => false, 'default' => array(), 'filter' => 'join');
$config->story->form->edit['oldDocs']     = array('type' => 'array', 'required' => false, 'default' => array());
$config->story->form->edit['docVersions'] = array('type' => 'array', 'required' => false, 'default' => array());

$config->story->form->change['docs']        = array('type' => 'array', 'required' => false, 'default' => array(), 'filter' => 'join');
$config->story->form->change['oldDocs']     = array('type' => 'array', 'required' => false, 'default' => array());
$config->story->form->change['docVersions'] = array('type' => 'array', 'required' => false, 'default' => array());

if(!isset($config->story->report)) $config->story->report = new stdclass();
$config->story->report->basicData = array();
$config->story->report->basicData['basic'][] = 'story';
$config->story->report->basicData['basic'][] = 'dev';
$config->story->report->basicData['basic'][] = 'test';
$config->story->report->basicData['basic'][] = 'done';
$config->story->report->basicData['basic'][] = 'closed';
$config->story->report->basicData['basic'][] = 'changed';

$config->story->report->progressData = array();
$config->story->report->progressData['progress'][] = 'story';
$config->story->report->progressData['progress'][] = 'done';
$config->story->report->progressData['progress'][] = 'dev';
$config->story->report->progressData['progress'][] = 'test';

$config->story->report->exportFields = array();
$config->story->report->exportFields['basic']['basic']      = array('storyNum', 'storyScale', 'devNum', 'devScale', 'testNum', 'testScale', 'doneNum', 'doneScale', 'closedNum', 'closedScale', 'changedNum', 'changedScale');
$config->story->report->exportFields['basic']['statusMap']  = 'image';
$config->story->report->exportFields['basic']['stageMap']   = 'image';
$config->story->report->exportFields['basic']['productMap'] = 'image';
$config->story->report->exportFields['basic']['sourceMap']  = 'image';
$config->story->report->exportFields['basic']['priMap']     = 'image';
$config->story->report->exportFields['basic']['typeMap']    = 'image';
$config->story->report->exportFields['basic']['userMap']    = 'image';

$config->story->report->exportFields['progress']['basic']         = array('storyNum', 'storyScale', 'doneNum', 'doneScale', 'devNum', 'devScale', 'testNum', 'testScale');
$config->story->report->exportFields['progress']['devRate']       = 'image';
$config->story->report->exportFields['progress']['testRate']      = 'image';
$config->story->report->exportFields['progress']['doneRate']      = 'image';
$config->story->report->exportFields['progress']['devScaleRate']  = 'image';
$config->story->report->exportFields['progress']['testScaleRate'] = 'image';
$config->story->report->exportFields['progress']['doneScaleRate'] = 'image';
$config->story->report->exportFields['progress']['stageMap']      = 'image';
