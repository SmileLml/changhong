<?php
$lang->task->noExecution = '【' . $lang->executionCommon . '】' . '不能为空！';
$lang->task->docs        = '相关文档';
$lang->task->docVersions = '文档版本';
$lang->task->feedback    = '反馈';
$lang->task->docSyncTips = '该文档有新版本，可切换版本更新。';

$lang->task->report->tpl = new stdclass();
$lang->task->report->tpl->filter  = '列表筛选条件：';
$lang->task->report->tpl->feature = '%s的任务';
$lang->task->report->tpl->search  = '%s%s%s';
$lang->task->report->tpl->multi   = '（%s）%s（%s）';

$lang->task->report->notice           = '请选择统计内容';
$lang->task->report->untitled         = '未命名';
$lang->task->report->errorExportChart = '该浏览器不支持Canvas图像导出功能，请换其他浏览器。';

$lang->task->report->typeList['basic']    = '基本统计';
$lang->task->report->typeList['progress'] = '进度分析';
$lang->task->report->typeList['resource'] = '资源分析';

$lang->task->report->tips = new stdclass();
$lang->task->report->tips->doneRate       = '%s任务数÷任务数×100%%';
$lang->task->report->tips->taskRate       = '已消耗工时数÷（已消耗工时数+剩余工时数）×100%';
$lang->task->report->tips->devRate        = '检索列表页面类型为%s的任务中，%s的任务数÷任务数×100%%';
$lang->task->report->tips->testRate       = '检索列表页面类型为%s的任务中，%s的任务数÷任务数×100%%';
$lang->task->report->tips->progress       = '进度=总计消耗 ÷（总计消耗+预计剩余）×100%';
$lang->task->report->tips->bugConsumeRate = 'Bug转任务的消耗工时÷任务已消耗工时*100%';
$lang->task->report->tips->bugRate        = 'Bug转任务的数量÷任务数量*100%';
$lang->task->report->tips->notFinished    = '暂时没有%s的任务。';
$lang->task->report->tips->totalConsumed  = '所有任务消耗工时求和，过滤父任务。';
$lang->task->report->tips->assigned       = '暂时没有被指派的任务';

$lang->task->report->taskNum   = '任务数';
$lang->task->report->doneNum   = '%s任务数';
$lang->task->report->consumed  = '已消耗工时数';
$lang->task->report->left      = '剩余工时数';
$lang->task->report->doneRate  = '任务完成率';
$lang->task->report->taskRate  = '任务进度';
$lang->task->report->devRate   = '%s类型任务完成率';
$lang->task->report->testRate  = '%s类型任务完成率';
$lang->task->report->taskType  = '任务类型';
$lang->task->report->taskCost  = '总计消耗';
$lang->task->report->leftTime  = '预计剩余';
$lang->task->report->progress  = '进度';
$lang->task->report->dailyNum  = '每日完成任务数量统计图';
$lang->task->report->typeMap   = '不同类型任务的进度统计表';
$lang->task->report->statusMap = '不同类型任务的状态统计表';

$lang->task->report->member             = '成员名称';
$lang->task->report->effort             = '执行可用工时';
$lang->task->report->realConsumed       = '任务实际消耗工时';
$lang->task->report->consumedHour       = '实际消耗工时';
$lang->task->report->consumedRate       = '工时投入率';
$lang->task->report->teamEfforts        = '按团队成员统计的工时投入';
$lang->task->report->userEfforts        = '按团队成员统计的任务消耗工时数';
$lang->task->report->bugTaskNum         = 'Bug转任务的数量';
$lang->task->report->bugConsume         = 'Bug转任务的消耗工时';
$lang->task->report->bugRate            = 'Bug转任务的数量占比';
$lang->task->report->bugConsumeRate     = 'Bug转任务的消耗工时占比';
$lang->task->report->statusDistribution = '任务状态分布';
$lang->task->report->assignDistribution = '任务指派给分布';
$lang->task->report->ownerDistribution  = '任务完成者分布';
$lang->task->report->moduleDistribution = '任务一级模块分布';
$lang->task->report->typeDistribution   = '任务类型分布';
$lang->task->report->priDistribution    = '任务优先级分布';
$lang->task->report->reasonDistribution = '任务关闭原因分布';
$lang->task->report->workAssignSummary  = '任务指派汇总表';
$lang->task->report->workSummary        = '任务完成汇总表';
$lang->task->report->executionConsumed  = '%s总消耗';
$lang->task->report->execution          = '所属%s';
$lang->task->report->projectDailyNum    = '项目周期内每日完成任务数量柱状图(近14天)';
