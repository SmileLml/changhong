<?php
if(!isset($config->testcase->reportChart)) $config->testcase->reportChart = new stdclass();

$config->testcase->reportChart->exportFields['basic']['basic']             = array('storyNum', 'caseNum', 'hasCaseStoryNum', 'caseCoverage', 'caseDensity');
$config->testcase->reportChart->exportFields['basic']['userCreatedCases']  = 'image';
$config->testcase->reportChart->exportFields['basic']['userExecutedCases'] = 'image';
$config->testcase->reportChart->exportFields['basic']['productMap']        = 'image';
$config->testcase->reportChart->exportFields['basic']['statusMap']         = 'image';
$config->testcase->reportChart->exportFields['basic']['priMap']            = 'image';
$config->testcase->reportChart->exportFields['basic']['resultMap']         = 'image';
$config->testcase->reportChart->exportFields['basic']['typeMap']           = 'image';
