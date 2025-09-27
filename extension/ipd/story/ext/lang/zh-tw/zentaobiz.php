<?php
global $app;

$lang->story->feedback    = '反饋';
$lang->story->docs        = '相關文檔';
$lang->story->docVersions = '文檔版本號';
$lang->story->docSyncTips = '該文檔存在最新版本';

$lang->story->report->typeList['basic']    = '基本統計';
$lang->story->report->typeList['progress'] = '進度分析';

$lang->story->report->tpl = new stdclass();
$lang->story->report->tpl->filter  = '列表篩選條件：';
$lang->story->report->tpl->feature = '%s的需求';
$lang->story->report->tpl->search  = '%s%s%s';
$lang->story->report->tpl->multi   = '（%s）%s（%s）';

$lang->story->report->tips = new stdclass();
$lang->story->report->tips->changedNum    = '需求變更日期晚于%s實際開始日期，早于%s實際關閉日期，且狀態不為變更中和評審中的需求';
$lang->story->report->tips->devRate       = '按條目統計的需求研發完畢率：研發完畢需求條目數÷需求條目數*100%。';
$lang->story->report->tips->devScaleRate  = '按規模統計的需求研發完畢率：研發完畢需求規模數÷需求規模數*100%。';
$lang->story->report->tips->testRate      = '按條目統計的需求測試完畢率：測試完畢需求條目數÷需求條目數*100%。';
$lang->story->report->tips->testScaleRate = '按規模統計的需求測試完畢率：測試完畢需求規模數÷需求規模數*100%。';
$lang->story->report->tips->doneRate      = '按條目統計的需求完成率：已完成需求條目數÷需求條目數*100%。';
$lang->story->report->tips->doneScaleRate = '按規模統計的需求完成率：已完成需求規模數÷需求規模數*100%。';
$lang->story->report->tips->productMap    = '最多展示二級模組';

$lang->story->report->notice        = '請選擇統計內容';
$lang->story->report->storyNum      = '需求條目數';
$lang->story->report->storyScale    = '需求規模數';
$lang->story->report->devNum        = '研發完畢需求條目數';
$lang->story->report->devScale      = '研發完畢需求規模數';
$lang->story->report->testNum       = '測試完畢需求條目數';
$lang->story->report->testScale     = '測試完畢需求規模數';
$lang->story->report->doneNum       = '已完成需求條目數';
$lang->story->report->doneScale     = '已完成需求規模數';
$lang->story->report->closedNum     = '已關閉需求條目數';
$lang->story->report->closedScale   = '已關閉需求規模數';
$lang->story->report->changedNum    = '變更的需求條目數';
$lang->story->report->changedScale  = '變更的需求規模數';
$lang->story->report->statusMap     = '需求狀態分佈';
$lang->story->report->stageMap      = '需求階段分佈';
$lang->story->report->productMap    = '需求來源產品模組分佈';
$lang->story->report->sourceMap     = '需求來源分佈';
$lang->story->report->moduleMap     = '需求所在一級模組分佈';
$lang->story->report->priMap        = '需求優先順序分佈';
$lang->story->report->categoryMap   = '需求所屬類別分佈';
$lang->story->report->userMap       = '需求由誰創建分佈';
$lang->story->report->devRate       = '按條目統計的需求研發完畢率';
$lang->story->report->devScaleRate  = '按規模統計的需求研發完畢率';
$lang->story->report->testRate      = '按條目統計的需求測試完畢率';
$lang->story->report->testScaleRate = '按規模統計的需求測試完畢率';
$lang->story->report->doneRate      = '按條目統計的需求完成率';
$lang->story->report->doneScaleRate = '按規模統計的需求完成率';
