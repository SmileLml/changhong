<?php
$lang->project->reportSettings = new stdclass();
$lang->project->reportSettings->common      = '報表';
$lang->project->reportSettings->subtitle    = '為確保統計準確性, 採用%s列表的全部數據進行統計。';
$lang->project->reportSettings->notice      = '請選擇統計內容';
$lang->project->reportSettings->name        = '%s名稱';
$lang->project->reportSettings->storyNum    = '需求數';
$lang->project->reportSettings->taskNum     = '任務數';
$lang->project->reportSettings->undoneTask  = '剩餘任務數';
$lang->project->reportSettings->undoneStory = '剩餘需求數';
$lang->project->reportSettings->left        = '剩餘工時數';
$lang->project->reportSettings->consumed    = '已消耗工時數';
$lang->project->reportSettings->progress    = '%s進度';
$lang->project->reportSettings->execution   = '迭代';

$lang->project->reportSettings->devRate       = '開發效率';
$lang->project->reportSettings->passRate      = '需求驗收通過率';
$lang->project->reportSettings->storyDoneRate = '需求按計劃完成率';
$lang->project->reportSettings->devDoneRate   = '開發任務完成率';
$lang->project->reportSettings->testDoneRate  = '測試任務完成率';
$lang->project->reportSettings->testDensity   = '測試缺陷密度';
$lang->project->reportSettings->total         = '%s數';
$lang->project->reportSettings->doingNum      = '進行中%s數';
$lang->project->reportSettings->closedNum     = '已關閉%s數';
$lang->project->reportSettings->delayNum      = '延期的%s數';
$lang->project->reportSettings->closedRate    = '%s關閉率';
$lang->project->reportSettings->delayRate     = '%s延期率';
$lang->project->reportSettings->statusMap     = '%s狀態分佈';
$lang->project->reportSettings->doingSummary  = '進行中%s彙總表';
$lang->project->reportSettings->closedSummary = '已關閉%s彙總表';

$lang->project->reportSettings->typeList['execution']['basic']    = '基本統計';
$lang->project->reportSettings->typeList['execution']['task']     = '任務統計';
$lang->project->reportSettings->typeList['execution']['progress'] = '進度分析';
$lang->project->reportSettings->typeList['execution']['resource'] = '資源分析';
$lang->project->reportSettings->typeList['bug']['basic']          = '基本統計';
$lang->project->reportSettings->typeList['bug']['progress']       = '進度分析';

$lang->project->reportSettings->tips = new stdclass();
$lang->project->reportSettings->tips->closedRate    = '已關閉%s數÷%s數*100%';
$lang->project->reportSettings->tips->delayRate     = '延期的%s數÷%s數*100%';
$lang->project->reportSettings->tips->doingSummary  = '需求數: %s中所有需求數量之和; 剩餘需求數: %s中所有未關閉的需求數量之和; 任務數: %s中所有任務數量之和; 剩餘任務數: %s中狀態不是“已關閉”和“已完成”的任務數量求和; 剩餘工時數: %s中所有任務剩餘工時數求和, 過濾父任務, 過濾狀態為已取消和已關閉的任務; 消耗工時數: %s中所有任務剩餘工時數求和, 過濾父任務; %s進度: 消耗工時數 ÷ (消耗工時數+剩餘工時數) * 100% 。';
$lang->project->reportSettings->tips->closedSummary = "執行關閉後第二天顯示統計數據，統計規則如下：開發效率: %s關閉時已交付的研發需求規模數÷按%s統計的任務消耗工時數; 需求驗收通過率: %s關閉時驗收通過的研發需求數÷按%s統計的有效研發需求數; 需求按計劃完成率: %s關閉時已交付的研發需求數÷截止%s開始當天的研發需求數; 開發任務完成率: %s關閉時已完成的開發類型任務數÷按%s統計的開發任務數; 測試任務完成率: %s關閉時已完成的測試類型任務數÷按%s統計的測試任務數; 測試缺陷密度: 按%s統計的新增有效Bug數÷%s關閉時研發完畢的研發需求規模數";

$lang->project->executionReport = $lang->execution->common . '統計報表';
$lang->project->reportBug       = 'Bug統計報表';