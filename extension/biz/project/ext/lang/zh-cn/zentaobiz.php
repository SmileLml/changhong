<?php
$lang->project->reportSettings = new stdclass();
$lang->project->reportSettings->common      = '报表';
$lang->project->reportSettings->subtitle    = '为确保统计准确性, 采用%s列表的全部数据进行统计。';
$lang->project->reportSettings->notice      = '请选择统计内容';
$lang->project->reportSettings->name        = '%s名称';
$lang->project->reportSettings->storyNum    = '需求数';
$lang->project->reportSettings->taskNum     = '任务数';
$lang->project->reportSettings->undoneTask  = '剩余任务数';
$lang->project->reportSettings->undoneStory = '剩余需求数';
$lang->project->reportSettings->left        = '剩余工时数';
$lang->project->reportSettings->consumed    = '已消耗工时数';
$lang->project->reportSettings->progress    = '%s进度';
$lang->project->reportSettings->execution   = '迭代';

$lang->project->reportSettings->devRate       = '开发效率';
$lang->project->reportSettings->passRate      = '需求验收通过率';
$lang->project->reportSettings->storyDoneRate = '需求按计划完成率';
$lang->project->reportSettings->devDoneRate   = '开发任务完成率';
$lang->project->reportSettings->testDoneRate  = '测试任务完成率';
$lang->project->reportSettings->testDensity   = '测试缺陷密度';
$lang->project->reportSettings->total         = '%s数';
$lang->project->reportSettings->doingNum      = '进行中%s数';
$lang->project->reportSettings->closedNum     = '已关闭%s数';
$lang->project->reportSettings->delayNum      = '延期的%s数';
$lang->project->reportSettings->closedRate    = '%s关闭率';
$lang->project->reportSettings->delayRate     = '%s延期率';
$lang->project->reportSettings->statusMap     = '%s状态分布';
$lang->project->reportSettings->doingSummary  = '进行中%s汇总表';
$lang->project->reportSettings->closedSummary = '已关闭%s汇总表';

$lang->project->reportSettings->typeList['execution']['basic']    = '基本统计';
$lang->project->reportSettings->typeList['execution']['task']     = '任务统计';
$lang->project->reportSettings->typeList['execution']['progress'] = '进度分析';
$lang->project->reportSettings->typeList['execution']['resource'] = '资源分析';
$lang->project->reportSettings->typeList['bug']['basic']          = '基本统计';
$lang->project->reportSettings->typeList['bug']['progress']       = '进度分析';

$lang->project->reportSettings->tips = new stdclass();
$lang->project->reportSettings->tips->closedRate    = '已关闭%s数÷%s数*100%';
$lang->project->reportSettings->tips->delayRate     = '延期的%s数÷%s数*100%';
$lang->project->reportSettings->tips->doingSummary  = '需求数: %s中所有需求数量之和; 剩余需求数: %s中所有未关闭的需求数量之和; 任务数: %s中所有任务数量之和; 剩余任务数: %s中状态不是“已关闭”和“已完成”的任务数量求和; 剩余工时数: %s中所有任务剩余工时数求和, 过滤父任务, 过滤状态为已取消和已关闭的任务; 消耗工时数: %s中所有任务剩余工时数求和, 过滤父任务; %s进度: 消耗工时数 ÷ (消耗工时数+剩余工时数) * 100% 。';
$lang->project->reportSettings->tips->closedSummary = "执行关闭后第二天显示统计数据，统计规则如下：开发效率: %s关闭时已交付的研发需求规模数÷按%s统计的任务消耗工时数; 需求验收通过率: %s关闭时验收通过的研发需求数÷按%s统计的有效研发需求数; 需求按计划完成率: %s关闭时已交付的研发需求数÷截止%s开始当天的研发需求数; 开发任务完成率: %s关闭时已完成的开发类型任务数÷按%s统计的开发任务数; 测试任务完成率: %s关闭时已完成的测试类型任务数÷按%s统计的测试任务数; 测试缺陷密度: 按%s统计的新增有效Bug数÷%s关闭时研发完毕的研发需求规模数";

$lang->project->executionReport = $lang->execution->common . '统计报表';
$lang->project->reportBug       = 'Bug统计报表';