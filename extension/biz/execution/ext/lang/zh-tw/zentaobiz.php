<?php
$lang->execution->report = new stdclass();
$lang->execution->report->typeList['bug']['basic']    = '基本統計';
$lang->execution->report->typeList['bug']['progress'] = '進度分析';

$lang->execution->report->browseType['bug']      = 'Bug';
$lang->execution->report->browseType['testcase'] = '用例';

$lang->execution->report->common           = '報表';
$lang->execution->report->subtitle         = '為確保統計準確性, 採用%s列表的全部數據進行統計。';
$lang->execution->report->notice           = '請選擇統計內容';
$lang->execution->report->untitled         = '未命名';
$lang->execution->report->errorExportChart = '該瀏覽器不支持Canvas圖像導出功能，請換其他瀏覽器。';

$lang->execution->report->bug = new stdclass();
$lang->execution->report->bug->count              = 'Bug數';
$lang->execution->report->bug->total              = 'Bug總數';
$lang->execution->report->bug->effective          = '有效Bug數';
$lang->execution->report->bug->useCase            = '執行用例產生的Bug數';
$lang->execution->report->bug->active             = '激活Bug數';
$lang->execution->report->bug->fixed              = '已修復Bug數';
$lang->execution->report->bug->developedStory     = '研發完畢的需求數';
$lang->execution->report->bug->defect             = '研發完畢的需求的缺陷密度';
$lang->execution->report->bug->efficient          = 'Bug有效率';
$lang->execution->report->bug->fixedRate          = 'Bug修復率';
$lang->execution->report->bug->caseBugRate        = '用例產生的Bug占比';
$lang->execution->report->bug->severityMap        = 'Bug嚴重程度分佈';
$lang->execution->report->bug->priMap             = 'Bug優先順序分佈';
$lang->execution->report->bug->resolutionMap      = 'Bug解決方案分佈';
$lang->execution->report->bug->typeMap            = 'Bug類型分佈';
$lang->execution->report->bug->dailyNum           = '每日新增、解決Bug數、關閉Bug數(折線圖)';
$lang->execution->report->bug->userCreatedBugs    = '按團隊成員統計的創建Bug數';
$lang->execution->report->bug->userResolvedBugs   = '按團隊成員統計的解決Bug數';
$lang->execution->report->bug->statusDistribution = 'Bug狀態分佈';
$lang->execution->report->bug->productMap         = 'Bug來源產品模組分佈';

$lang->execution->report->bug->dailyTitles[] = '每日新增';
$lang->execution->report->bug->dailyTitles[] = '解決Bug數';
$lang->execution->report->bug->dailyTitles[] = '關閉Bug數';

$lang->execution->report->tips = new stdclass();
$lang->execution->report->tips->effective   = 'Bug列表中解決方案為已解決、延期處理和不予解決或狀態為激活的Bug';
$lang->execution->report->tips->efficient   = '有效Bug數÷Bug總數';
$lang->execution->report->tips->fixedRate   = '已修復Bug數÷有效Bug數';
$lang->execution->report->tips->caseBugRate = '執行用例產生的Bug數÷Bug總數';
$lang->execution->report->tips->defect      = '有效Bug數÷研發完畢的需求數';
$lang->execution->report->tips->fixed       = 'Bug列表中解決方案為已解決且狀態為已關閉的Bug數';
$lang->execution->report->tips->productMap  = '最多展示二級模組';

$lang->execution->reportBug = 'Bug統計報表';