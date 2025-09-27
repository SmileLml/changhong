<?php
$lang->task->noExecution = '[' . $lang->executionCommon . ']' . 'cannot be empty!';
$lang->task->docs        = 'Doc';
$lang->task->docVersions = 'Doc version';
$lang->task->feedback    = 'Feedback';
$lang->task->docSyncTips = 'This document has the latest version available';

$lang->task->report->tpl = new stdclass();
$lang->task->report->tpl->filter  = 'List filter: ';
$lang->task->report->tpl->feature = '%s tasks';
$lang->task->report->tpl->search  = '%s %s %s';
$lang->task->report->tpl->multi   = '(%s) %s (%s)';

$lang->task->report->notice           = 'Statistics content';
$lang->task->report->untitled         = 'Untitled';
$lang->task->report->errorExportChart = 'Your browser does not support Canvas exporting. Try other browsers.';

$lang->task->report->typeList['basic']    = 'Basic statistics';
$lang->task->report->typeList['progress'] = 'Progress analysis';
$lang->task->report->typeList['resource'] = 'Resource analysis';

$lang->task->report->tips = new stdclass();
$lang->task->report->tips->doneRate       = '%s task / total * 100%%';
$lang->task->report->tips->taskRate       = 'Consumed time / (consumed time + left time) * 100%';
$lang->task->report->tips->devRate        = 'Task type is %s, the number of %s tasks / total * 100%%';
$lang->task->report->tips->testRate       = 'Task type is %s, the number of %s tasks / total * 100%%';
$lang->task->report->tips->progress       = 'Progress = cost time / (cost time + left time) * 100%';
$lang->task->report->tips->bugConsumeRate = 'Bug task consumed / total * 100%';
$lang->task->report->tips->bugRate        = 'Bug to task / total * 100%';
$lang->task->report->tips->notFinished    = 'No %s task';
$lang->task->report->tips->totalConsumed  = 'Sum up the time consumed by all tasks and filter the parent tasks.';
$lang->task->report->tips->assigned       = 'No assigned tasks.';

$lang->task->report->taskNum   = 'Task count';
$lang->task->report->doneNum   = '%s task count';
$lang->task->report->consumed  = 'Consumed time';
$lang->task->report->left      = 'Left time';
$lang->task->report->doneRate  = 'Task completion rate';
$lang->task->report->taskRate  = 'Task progress';
$lang->task->report->devRate   = '%s task rate';
$lang->task->report->testRate  = '%s task rate';
$lang->task->report->taskType  = 'Task type';
$lang->task->report->taskCost  = 'Total cost';
$lang->task->report->leftTime  = 'Left time';
$lang->task->report->progress  = 'Progress';
$lang->task->report->dailyNum  = 'Daily completed task count statistics chart';
$lang->task->report->typeMap   = 'Different type tasks progress statistics table';
$lang->task->report->statusMap = 'Different type tasks status statistics table';

$lang->task->report->member             = 'Member';
$lang->task->report->effort             = 'Effort';
$lang->task->report->realConsumed       = 'Task consumed time';
$lang->task->report->consumedHour       = 'Real consumed time';
$lang->task->report->consumedRate       = 'Consumed rate';
$lang->task->report->teamEfforts        = 'Detailed team effort distribution';
$lang->task->report->userEfforts        = 'Team effort distribution';
$lang->task->report->bugTaskNum         = 'Bug to task count';
$lang->task->report->bugConsume         = 'Bug to task consumed';
$lang->task->report->bugRate            = 'Bug to task rate';
$lang->task->report->bugConsumeRate     = 'Bug to task consume rate';
$lang->task->report->statusDistribution = 'Task status distribution';
$lang->task->report->assignDistribution = 'Task assign distribution';
$lang->task->report->ownerDistribution  = 'Task owner distribution';
$lang->task->report->moduleDistribution = 'Task first level module distribution';
$lang->task->report->typeDistribution   = 'Task type distribution';
$lang->task->report->priDistribution    = 'Task priority distribution';
$lang->task->report->reasonDistribution = 'Task close reason distribution';
$lang->task->report->workAssignSummary  = 'Task assign summary';
$lang->task->report->workSummary        = 'Task summary';
$lang->task->report->executionConsumed  = '%s Total Cost';
$lang->task->report->execution          = '%s';
$lang->task->report->projectDailyNum    = 'Bar chart of daily completed tasks during the project cycle (Nearly 14 days)';

