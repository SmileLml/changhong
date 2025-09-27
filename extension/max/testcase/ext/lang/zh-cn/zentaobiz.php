<?php
if(!isset($lang->testcase->report)) $lang->testcase->report = new stdclass();
$lang->testcase->report->typeList['basic'] = '基本统计';

$lang->testcase->report->common           = '报表';
$lang->testcase->report->subtitle         = '为确保统计准确性, 采用%s列表的全部数据进行统计。';
$lang->testcase->report->notice           = '请选择统计内容';
$lang->testcase->report->untitled         = '未命名';
$lang->testcase->report->errorExportChart = '该浏览器不支持Canvas图像导出功能，请换其他浏览器。';

$lang->testcase->report->storyNum          = '非父研发需求数';
$lang->testcase->report->caseNum           = '用例数';
$lang->testcase->report->hasCaseStoryNum   = '有用例的需求数';
$lang->testcase->report->caseCoverage      = '需求用例覆盖率';
$lang->testcase->report->caseDensity       = '需求用例密度';
$lang->testcase->report->userCreatedCases  = '按团队成员统计的创建用例数';
$lang->testcase->report->userExecutedCases = '按团队成员统计的执行用例次数';
$lang->testcase->report->statusMap         = '用例状态分布';
$lang->testcase->report->priMap            = '用例优先级分布';
$lang->testcase->report->resultMap         = '用例结果分布';
$lang->testcase->report->typeMap           = '用例类型分布';
$lang->testcase->report->productMap        = '用例来源产品模块分布';

$lang->testcase->report->tips = new stdclass();
$lang->testcase->report->tips->caseCoverage = '有用例的需求数÷非父研发需求数*100%';
$lang->testcase->report->tips->caseDensity  = '用例数÷非父研发需求数';
$lang->testcase->report->tips->productMap   = '最多展示二级模块';

$lang->testcase->reportAction = '用例统计报表';
