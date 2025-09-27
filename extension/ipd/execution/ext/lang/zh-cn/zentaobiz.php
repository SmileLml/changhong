<?php
$lang->execution->report = new stdclass();
$lang->execution->report->typeList['bug']['basic']    = '基本统计';
$lang->execution->report->typeList['bug']['progress'] = '进度分析';

$lang->execution->report->browseType['bug']      = 'Bug';
$lang->execution->report->browseType['testcase'] = '用例';

$lang->execution->report->common           = '报表';
$lang->execution->report->subtitle         = '为确保统计准确性, 采用%s列表的全部数据进行统计。';
$lang->execution->report->notice           = '请选择统计内容';
$lang->execution->report->untitled         = '未命名';
$lang->execution->report->errorExportChart = '该浏览器不支持Canvas图像导出功能，请换其他浏览器。';

$lang->execution->report->bug = new stdclass();
$lang->execution->report->bug->count              = 'Bug数';
$lang->execution->report->bug->total              = 'Bug总数';
$lang->execution->report->bug->effective          = '有效Bug数';
$lang->execution->report->bug->useCase            = '执行用例产生的Bug数';
$lang->execution->report->bug->active             = '激活Bug数';
$lang->execution->report->bug->fixed              = '已修复Bug数';
$lang->execution->report->bug->developedStory     = '研发完毕的需求数';
$lang->execution->report->bug->defect             = '研发完毕的需求的缺陷密度';
$lang->execution->report->bug->efficient          = 'Bug有效率';
$lang->execution->report->bug->fixedRate          = 'Bug修复率';
$lang->execution->report->bug->caseBugRate        = '用例产生的Bug占比';
$lang->execution->report->bug->severityMap        = 'Bug严重程度分布';
$lang->execution->report->bug->priMap             = 'Bug优先级分布';
$lang->execution->report->bug->resolutionMap      = 'Bug解决方案分布';
$lang->execution->report->bug->typeMap            = 'Bug类型分布';
$lang->execution->report->bug->dailyNum           = '每日新增、解决Bug数、关闭Bug数(折线图)';
$lang->execution->report->bug->userCreatedBugs    = '按团队成员统计的创建Bug数';
$lang->execution->report->bug->userResolvedBugs   = '按团队成员统计的解决Bug数';
$lang->execution->report->bug->statusDistribution = 'Bug状态分布';
$lang->execution->report->bug->productMap         = 'Bug来源产品模块分布';

$lang->execution->report->bug->dailyTitles[] = '每日新增';
$lang->execution->report->bug->dailyTitles[] = '解决Bug数';
$lang->execution->report->bug->dailyTitles[] = '关闭Bug数';

$lang->execution->report->tips = new stdclass();
$lang->execution->report->tips->effective   = 'Bug列表中解决方案为已解决、延期处理和不予解决或状态为激活的Bug';
$lang->execution->report->tips->efficient   = '有效Bug数÷Bug总数';
$lang->execution->report->tips->fixedRate   = '已修复Bug数÷有效Bug数';
$lang->execution->report->tips->caseBugRate = '执行用例产生的Bug数÷Bug总数';
$lang->execution->report->tips->defect      = '有效Bug数÷研发完毕的需求数';
$lang->execution->report->tips->fixed       = 'Bug列表中解决方案为已解决且状态为已关闭的Bug数';
$lang->execution->report->tips->productMap  = '最多展示二级模块';

$lang->execution->reportBug = 'Bug统计报表';