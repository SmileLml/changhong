<?php
$lang->project->reportSettings = new stdclass();
$lang->project->reportSettings->common      = 'Report';
$lang->project->reportSettings->subtitle    = 'To ensure statistical accuracy, all data from the %s list will be used for statistics.';
$lang->project->reportSettings->notice      = 'Statistical content';
$lang->project->reportSettings->name        = '%s name';
$lang->project->reportSettings->storyNum    = 'Stories';
$lang->project->reportSettings->taskNum     = 'Tasks';
$lang->project->reportSettings->undoneTask  = 'Undone tasks';
$lang->project->reportSettings->undoneStory = 'Undone stories';
$lang->project->reportSettings->left        = 'Left';
$lang->project->reportSettings->consumed    = 'Cost';
$lang->project->reportSettings->progress    = '%s progress';
$lang->project->reportSettings->execution   = 'Execution';

$lang->project->reportSettings->devRate       = 'Development Efficiency';
$lang->project->reportSettings->passRate      = 'Story Acceptance Rate';
$lang->project->reportSettings->storyDoneRate = 'Story Completion Rate';
$lang->project->reportSettings->devDoneRate   = 'Development Task Completion Rate';
$lang->project->reportSettings->testDoneRate  = 'Testing Task Completion Rate';
$lang->project->reportSettings->testDensity   = 'Test Defect Density';
$lang->project->reportSettings->total         = 'Total %s';
$lang->project->reportSettings->doingNum      = 'In progress %s';
$lang->project->reportSettings->closedNum     = 'Closed %s';
$lang->project->reportSettings->delayNum      = 'Delayed %s';
$lang->project->reportSettings->closedRate    = '%s closed Rate';
$lang->project->reportSettings->delayRate     = '%s delayed Rate';
$lang->project->reportSettings->statusMap     = '%s status distribution';
$lang->project->reportSettings->doingSummary  = 'In progress %s summary table';
$lang->project->reportSettings->closedSummary = 'Closed %s summary table';

$lang->project->reportSettings->typeList['execution']['basic']    = 'Basic Statistics';
$lang->project->reportSettings->typeList['execution']['task']     = 'Task Statistics';
$lang->project->reportSettings->typeList['execution']['progress'] = 'Progress Analysis';
$lang->project->reportSettings->typeList['execution']['resource'] = 'Resource Analysis';
$lang->project->reportSettings->typeList['bug']['basic']          = 'Basic Statistics';
$lang->project->reportSettings->typeList['bug']['progress']       = 'Progress Analysis';

$lang->project->reportSettings->tips = new stdclass();
$lang->project->reportSettings->tips->closedRate    = 'Number of closed %s ÷ total %s * 100%';
$lang->project->reportSettings->tips->delayRate     = 'Number of delayed %s ÷ total %s * 100%';
$lang->project->reportSettings->tips->doingSummary  = 'Number of stories: The sum of all stories in %s; Remaining stories: The sum of all ongoing stories that are not closed in %s; Number of tasks: The sum of all tasks in %s; Remaining tasks: The sum of tasks in %s with a status that is not Closed or Completed; Remaining hours: The sum of remaining hours for all tasks in %s, filtering out parent tasks and tasks with statuses of Cancelled and Closed; Consumed hours: The sum of remaining hours for all tasks in %s, filtering out parent tasks; Progress in %s: Consumed hours ÷ (Consumed hours + Remaining hours) * 100%.';
$lang->project->reportSettings->tips->closedSummary = "Display statistical data on the second day after executing the shutdown, with the following statistical rules: Development Efficiency: %s closed delivered R&D stories scale ÷ task hours consumed according to %s; story Acceptance Rate: %s closed R&D stories accepted ÷ valid R&D stories according to %s; story Planned Completion Rate: %s closed delivered R&D stories ÷ R&D stories as of the start of %s; Development Task Completion Rate: %s closed completed development tasks ÷ development tasks according to %s; Testing Task Completion Rate: %s closed completed testing tasks ÷ testing tasks according to %s; Test Defect Density: New valid bugs according to %s ÷ R&D stories scale completed by %s closed.";

$lang->project->executionReport = $lang->execution->common . ' report';
$lang->project->reportBug       = 'Bug report';
