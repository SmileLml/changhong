<?php
if(!isset($config->execution->reportChart)) $config->execution->reportChart = new stdclass();

$config->execution->reportChart->exportFields = array();
$config->execution->reportChart->exportFields['bug']['basic']['basic']              = array('total', 'effective', 'useCase', 'fixed', 'developedStory', 'defect');
$config->execution->reportChart->exportFields['bug']['basic']['efficient']          = 'image';
$config->execution->reportChart->exportFields['bug']['basic']['fixedRate']          = 'image';
$config->execution->reportChart->exportFields['bug']['basic']['caseBugRate']        = 'image';
$config->execution->reportChart->exportFields['bug']['basic']['statusDistribution'] = 'image';
$config->execution->reportChart->exportFields['bug']['basic']['productMap']         = 'image';
$config->execution->reportChart->exportFields['bug']['basic']['severityMap']        = 'image';
$config->execution->reportChart->exportFields['bug']['basic']['priMap']             = 'image';
$config->execution->reportChart->exportFields['bug']['basic']['resolutionMap']      = 'image';
$config->execution->reportChart->exportFields['bug']['basic']['typeMap']            = 'image';

$config->execution->reportChart->exportFields['bug']['progress']['progress']         = array('total', 'active', 'fixed');
$config->execution->reportChart->exportFields['bug']['progress']['dailyNum']         = 'image';
$config->execution->reportChart->exportFields['bug']['progress']['userCreatedBugs']  = 'image';
$config->execution->reportChart->exportFields['bug']['progress']['userResolvedBugs'] = 'image';
