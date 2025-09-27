<?php
$lang->task->noExecution = '【' . $lang->executionCommon . '】' . '不能為空！';
$lang->task->docs        = '相關文檔';
$lang->task->docVersions = '文檔版本';
$lang->task->feedback    = '反饋';
$lang->task->docSyncTips = '該文檔有新版本，可切換版本更新。';

$lang->task->report->tpl = new stdclass();
$lang->task->report->tpl->filter  = '列表篩選條件：';
$lang->task->report->tpl->feature = '%s的任務';
$lang->task->report->tpl->search  = '%s%s%s';
$lang->task->report->tpl->multi   = '（%s）%s（%s）';

$lang->task->report->notice           = '請選擇統計內容';
$lang->task->report->untitled         = '未命名';
$lang->task->report->errorExportChart = '該瀏覽器不支持Canvas圖像導出功能，請換其他瀏覽器。';

$lang->task->report->typeList['basic']    = '基本統計';
$lang->task->report->typeList['progress'] = '進度分析';
$lang->task->report->typeList['resource'] = '資源分析';

$lang->task->report->tips = new stdclass();
$lang->task->report->tips->doneRate       = '%s任務數÷任務數×100%%';
$lang->task->report->tips->taskRate       = '已消耗工時數÷（已消耗工時數+剩餘工時數）×100%';
$lang->task->report->tips->devRate        = '檢索列表頁面類型為%s的任務中，%s的任務數÷任務數×100%%';
$lang->task->report->tips->testRate       = '檢索列表頁面類型為%s的任務中，%s的任務數÷任務數×100%%';
$lang->task->report->tips->progress       = '進度=總計消耗 ÷（總計消耗+預計剩餘）×100%';
$lang->task->report->tips->bugConsumeRate = 'Bug轉任務的消耗工時÷任務已消耗工時*100%';
$lang->task->report->tips->bugRate        = 'Bug轉任務的數量÷任務數量*100%';
$lang->task->report->tips->notFinished    = '暫時沒有%s的任務。';
$lang->task->report->tips->totalConsumed  = '所有任務消耗工時求和，過濾父任務。';
$lang->task->report->tips->assigned       = '暫時沒有被指派的任務';

$lang->task->report->taskNum   = '任務數';
$lang->task->report->doneNum   = '%s任務數';
$lang->task->report->consumed  = '已消耗工時數';
$lang->task->report->left      = '剩餘工時數';
$lang->task->report->doneRate  = '任務完成率';
$lang->task->report->taskRate  = '任務進度';
$lang->task->report->devRate   = '%s類型任務完成率';
$lang->task->report->testRate  = '%s類型任務完成率';
$lang->task->report->taskType  = '任務類型';
$lang->task->report->taskCost  = '總計消耗';
$lang->task->report->leftTime  = '預計剩餘';
$lang->task->report->progress  = '進度';
$lang->task->report->dailyNum  = '每日完成任務數量統計圖';
$lang->task->report->typeMap   = '不同類型任務的進度統計表';
$lang->task->report->statusMap = '不同類型任務的狀態統計表';

$lang->task->report->member             = '成員名稱';
$lang->task->report->effort             = '執行可用工時';
$lang->task->report->realConsumed       = '任務實際消耗工時';
$lang->task->report->consumedHour       = '實際消耗工時';
$lang->task->report->consumedRate       = '工時投入率';
$lang->task->report->teamEfforts        = '按團隊成員統計的工時投入';
$lang->task->report->userEfforts        = '按團隊成員統計的任務消耗工時數';
$lang->task->report->bugTaskNum         = 'Bug轉任務的數量';
$lang->task->report->bugConsume         = 'Bug轉任務的消耗工時';
$lang->task->report->bugRate            = 'Bug轉任務的數量占比';
$lang->task->report->bugConsumeRate     = 'Bug轉任務的消耗工時占比';
$lang->task->report->statusDistribution = '任務狀態分佈';
$lang->task->report->assignDistribution = '任務指派給分佈';
$lang->task->report->ownerDistribution  = '任務完成者分佈';
$lang->task->report->moduleDistribution = '任務一級模組分佈';
$lang->task->report->typeDistribution   = '任務類型分佈';
$lang->task->report->priDistribution    = '任務優先順序分佈';
$lang->task->report->reasonDistribution = '任務關閉原因分佈';
$lang->task->report->workAssignSummary  = '任務指派彙總表';
$lang->task->report->workSummary        = '任務完成彙總表';
$lang->task->report->executionConsumed  = '%s總消耗';
$lang->task->report->execution          = '所屬%s';
$lang->task->report->projectDailyNum    = '項目周期內每日完成任務數量柱狀圖(近14天)';
