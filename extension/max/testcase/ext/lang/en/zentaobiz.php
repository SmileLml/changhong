<?php
if(!isset($lang->testcase->report)) $lang->testcase->report = new stdclass();
$lang->testcase->report->typeList['basic'] = 'Basic Statistics';

$lang->testcase->report->common           = 'Report';
$lang->testcase->report->subtitle         = 'To ensure statistical accuracy, all data from the %s list is used for statistics.';
$lang->testcase->report->notice           = 'Statistics content';
$lang->testcase->report->untitled         = 'Untitled';
$lang->testcase->report->errorExportChart = 'Your browser does not support Canvas exporting. Try other browsers.';

$lang->testcase->report->storyNum          = 'Number of non parent story';
$lang->testcase->report->caseNum           = 'Number of case';
$lang->testcase->report->hasCaseStoryNum   = 'Number of story with case';
$lang->testcase->report->caseCoverage      = 'Story Case Coverage';
$lang->testcase->report->caseDensity       = 'Story Case Density';
$lang->testcase->report->userCreatedCases  = 'Number of Cases Created by Team Members';
$lang->testcase->report->userExecutedCases = 'Number of Cases Executed by Team Members';
$lang->testcase->report->statusMap         = 'Case Status Distribution';
$lang->testcase->report->priMap            = 'Case Priority Distribution';
$lang->testcase->report->resultMap         = 'Case Result Distribution';
$lang->testcase->report->typeMap           = 'Case Type Distribution';
$lang->testcase->report->productMap        = 'Case From Product Module Distribution';

$lang->testcase->report->tips = new stdclass();
$lang->testcase->report->tips->caseCoverage = 'Number of Stories with Cases ÷ Number of non parent story * 100%';
$lang->testcase->report->tips->caseDensity  = 'Number of Cases ÷ Number of non parent story';
$lang->testcase->report->tips->productMap   = 'Maximum display of secondary modules.';
