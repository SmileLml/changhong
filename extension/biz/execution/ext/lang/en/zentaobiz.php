<?php
$lang->execution->report = new stdclass();
$lang->execution->report->typeList['bug']['basic']    = 'Basic Statistics';
$lang->execution->report->typeList['bug']['progress'] = 'Progress Analysis';

$lang->execution->report->browseType['bug']      = 'Bug';
$lang->execution->report->browseType['testcase'] = 'case';

$lang->execution->report->common           = 'Report';
$lang->execution->report->subtitle         = 'To ensure statistical accuracy, all data from the %s list is used for statistics.';
$lang->execution->report->notice           = 'Statistics content';
$lang->execution->report->untitled         = 'Untitled';
$lang->execution->report->errorExportChart = 'Your browser does not support Canvas exporting. Try other browsers.';

$lang->execution->report->bug = new stdclass();
$lang->execution->report->bug->count              = 'Number of bugs';
$lang->execution->report->bug->total              = 'Total Bugs';
$lang->execution->report->bug->effective          = 'Effective Bugs';
$lang->execution->report->bug->useCase            = 'Bugs from Executed Use Cases';
$lang->execution->report->bug->active             = 'Active Bugs';
$lang->execution->report->bug->fixed              = 'Fixed Bugs';
$lang->execution->report->bug->developedStory     = 'Completed Requirements';
$lang->execution->report->bug->defect             = 'Defect Density of Completed Requirements';
$lang->execution->report->bug->efficient          = 'Bug Efficiency';
$lang->execution->report->bug->fixedRate          = 'Bug Fix Rate';
$lang->execution->report->bug->caseBugRate        = 'Proportion of Bugs from Use Cases';
$lang->execution->report->bug->severityMap        = 'Bug Severity Distribution';
$lang->execution->report->bug->priMap             = 'Bug Priority Distribution';
$lang->execution->report->bug->resolutionMap      = 'Bug Solution Distribution';
$lang->execution->report->bug->typeMap            = 'Bug Type Distribution';
$lang->execution->report->bug->dailyNum           = 'Daily New, Resolved, and Closed Bugs (Line Chart)';
$lang->execution->report->bug->userCreatedBugs    = 'Number of Bugs Created by Team Members';
$lang->execution->report->bug->userResolvedBugs   = 'Number of Bugs Resolved by Team Members';
$lang->execution->report->bug->statusDistribution = 'Bug status distribution';
$lang->execution->report->bug->productMap         = 'Bug From Product Module Distribution';

$lang->execution->report->bug->dailyTitles[] = 'Daily New';
$lang->execution->report->bug->dailyTitles[] = 'Resolved';
$lang->execution->report->bug->dailyTitles[] = 'Closed Bugs';

$lang->execution->report->tips = new stdclass();
$lang->execution->report->tips->effective   = 'Bugs in the list with solutions marked as resolved, deferred, or not resolved, or with an active status';
$lang->execution->report->tips->efficient   = 'Effective Bugs ÷ Total Bugs';
$lang->execution->report->tips->fixedRate   = 'Fixed Bugs ÷ Effective Bugs';
$lang->execution->report->tips->caseBugRate = 'Bugs generated from executed cases ÷ Total Bugs';
$lang->execution->report->tips->defect      = 'Effective Bugs ÷ Completed Requirements';
$lang->execution->report->tips->fixed       = 'The number of bugs in the bug list with resolved solutions and closed status';
$lang->execution->report->tips->productMap  = 'Maximum display of secondary modules.';

$lang->execution->reportBug = 'Bug report';