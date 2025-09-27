<?php
if(!isset($lang->testcase->report)) $lang->testcase->report = new stdclass();
$lang->testcase->report->typeList['basic'] = '基本統計';

$lang->testcase->report->common           = '報表';
$lang->testcase->report->subtitle         = '為確保統計準確性, 採用%s列表的全部數據進行統計。';
$lang->testcase->report->notice           = '請選擇統計內容';
$lang->testcase->report->untitled         = '未命名';
$lang->testcase->report->errorExportChart = '該瀏覽器不支持Canvas圖像導出功能，請換其他瀏覽器。';

$lang->testcase->report->storyNum          = '非父研發需求數';
$lang->testcase->report->caseNum           = '用例數';
$lang->testcase->report->hasCaseStoryNum   = '有用例的需求數';
$lang->testcase->report->caseCoverage      = '需求用例覆蓋率';
$lang->testcase->report->caseDensity       = '需求用例密度';
$lang->testcase->report->userCreatedCases  = '按團隊成員統計的創建用例數';
$lang->testcase->report->userExecutedCases = '按團隊成員統計的執行用例次數';
$lang->testcase->report->statusMap         = '用例狀態分佈';
$lang->testcase->report->priMap            = '用例優先順序分佈';
$lang->testcase->report->resultMap         = '用例結果分佈';
$lang->testcase->report->typeMap           = '用例類型分佈';
$lang->testcase->report->productMap        = '用例來源產品模組分佈';

$lang->testcase->report->tips = new stdclass();
$lang->testcase->report->tips->caseCoverage = '有用例的需求數÷非父研發需求數*100%';
$lang->testcase->report->tips->caseDensity  = '用例數÷非父研發需求數';
$lang->testcase->report->tips->productMap   = '最多展示二級模組';

$lang->testcase->reportAction = '用例統計報表';
