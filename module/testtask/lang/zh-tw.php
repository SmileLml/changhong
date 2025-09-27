<?php
/**
 * The testtask module zh-tw file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禪道軟件（青島）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     testtask
 * @version     $Id: zh-tw.php 4490 2013-02-27 03:27:05Z wyd621@gmail.com $
 * @link        https://www.zentao.net
 */
$lang->testtask->index            = "測試單首頁";
$lang->testtask->create           = "提交測試";
$lang->testtask->reportChart      = '報表統計';
$lang->testtask->delete           = "刪除測試單";
$lang->testtask->importUnitResult = "導入單元測試結果";
$lang->testtask->importUnit       = "導入單元測試"; //Fix bug custom required testtask.
$lang->testtask->browseUnits      = "單元測試列表";
$lang->testtask->unitCases        = "瀏覽單元測試用例列表";
$lang->testtask->view             = "概況";
$lang->testtask->edit             = "編輯測試單";
$lang->testtask->browse           = "測試單列表";
$lang->testtask->linkCase         = "關聯用例";
$lang->testtask->selectVersion    = "選擇測試單";
$lang->testtask->unlinkCase       = "移除";
$lang->testtask->batchUnlinkCases = "批量移除用例";
$lang->testtask->batchAssign      = "批量指派";
$lang->testtask->runCase          = "執行";
$lang->testtask->running          = "，正在執行中。";
$lang->testtask->runningLog       = "執行日誌";
$lang->testtask->runNode          = "由%s，在執行節點%s上 %s";
$lang->testtask->batchRun         = "批量執行";
$lang->testtask->results          = "結果";
$lang->testtask->createBug        = "提Bug";
$lang->testtask->assign           = '指派';
$lang->testtask->cases            = '用例';
$lang->testtask->groupCase        = "分組瀏覽用例";
$lang->testtask->pre              = '上一個';
$lang->testtask->next             = '下一個';
$lang->testtask->start            = "開始";
$lang->testtask->close            = "關閉";
$lang->testtask->wait             = "待測試";
$lang->testtask->block            = "阻塞";
$lang->testtask->activate         = "激活";
$lang->testtask->testing          = "測試中";
$lang->testtask->blocked          = "被阻塞";
$lang->testtask->done             = "已測試";
$lang->testtask->totalStatus      = "全部";
$lang->testtask->all              = '所有';
$lang->testtask->allTasks         = '所有測試';
$lang->testtask->collapseAll      = '全部摺疊';
$lang->testtask->expandAll        = '全部展開';
$lang->testtask->auto             = '自動化';
$lang->testtask->task             = '測試單';
$lang->testtask->run              = '執行編號';
$lang->testtask->job              = '構建任務';
$lang->testtask->compile          = '構建';
$lang->testtask->duration         = '持續時間';
$lang->testtask->myInvolved       = '由我參與';

$lang->testtask->viewAction     = "測試單概況";
$lang->testtask->casesAction    = '瀏覽用例列表';
$lang->testtask->activateAction = "激活測試單";
$lang->testtask->blockAction    = "阻塞測試單";
$lang->testtask->closeAction    = "關閉測試單";
$lang->testtask->startAction    = "開始測試單";
$lang->testtask->resultsAction  = "用例結果";
$lang->testtask->reportAction   = '用例報表統計';

$lang->testtask->id                = '編號';
$lang->testtask->common            = '測試單';
$lang->testtask->product           = '所屬' . $lang->productCommon;
$lang->testtask->project           = '所屬' . $lang->projectCommon;
$lang->testtask->execution         = '所屬' . $lang->execution->common;
$lang->testtask->type              = '測試類型';
$lang->testtask->build             = '提測構建';
$lang->testtask->owner             = '負責人';
$lang->testtask->members           = '參與人';
$lang->testtask->executor          = '執行人';
$lang->testtask->execTime          = '執行時間';
$lang->testtask->pri               = '優先順序';
$lang->testtask->name              = '測試單名稱';
$lang->testtask->unitName          = '單測名稱';
$lang->testtask->begin             = '開始日期';
$lang->testtask->end               = '結束日期';
$lang->testtask->realBegan         = '實際開始日期';
$lang->testtask->realFinishedDate  = '實際完成日期';
$lang->testtask->desc              = '描述';
$lang->testtask->mailto            = '抄送給';
$lang->testtask->status            = '當前狀態';
$lang->testtask->statusAB          = '狀態';
$lang->testtask->subStatus         = '子狀態';
$lang->testtask->testreport        = '相關測試報告';
$lang->testtask->assignCase        = '指派用例';
$lang->testtask->assignedTo        = '指派給';
$lang->testtask->linkVersion       = '版本';
$lang->testtask->lastRunAccount    = '執行人';
$lang->testtask->lastRunTime       = '執行時間';
$lang->testtask->lastRunResult     = '結果';
$lang->testtask->reportField       = '測試總結';
$lang->testtask->files             = '上傳附件';
$lang->testtask->case              = '用例';
$lang->testtask->version           = '版本';
$lang->testtask->caseResult        = '測試結果';
$lang->testtask->stepResults       = '步驟結果';
$lang->testtask->lastRunner        = '最後執行人';
$lang->testtask->lastRunDate       = '最後執行時間';
$lang->testtask->createdBy         = '由誰創建';
$lang->testtask->createdDate       = '創建時間';
$lang->testtask->date              = '測試時間';
$lang->testtask->deleted           = "已刪除";
$lang->testtask->resultFile        = "結果檔案";
$lang->testtask->caseCount         = '用例數';
$lang->testtask->passCount         = '成功';
$lang->testtask->failCount         = '失敗';
$lang->testtask->skipChangedCases  = '待確認的用例將會被忽略。';
$lang->testtask->summary           = '有%s個用例，失敗%s個，耗時%s。';
$lang->testtask->stepSummary       = '共有%s個步驟，%s個通過，%s個失敗。';
$lang->testtask->unitSummary       = '本頁共 <strong>%s</strong> 個單元測試結果。';
$lang->testtask->pageSummary       = '本頁共 <strong>%s</strong> 個測試單。';
$lang->testtask->mySummary         = '本頁共 <strong>%s</strong> 個測試單，待測試 <strong>%s</strong>，測試中 <strong>%s</strong>，被阻塞 <strong>%s</strong>。';
$lang->testtask->allSummary        = '本頁共 <strong>%s</strong> 個測試單，待測試 <strong>%s</strong>，測試中 <strong>%s</strong>，被阻塞 <strong>%s</strong>，已測試 <strong>%s</strong>。';
$lang->testtask->checkedAllSummary = '共選中 <strong>%total%</strong> 個測試單，待測試 <strong>%wait%</strong>，測試中 <strong>%testing%</strong>，被阻塞 <strong>%blocked%</strong>，已測試 <strong>%done%</strong>。';
$lang->testtask->emptyCases        = 'ID %s 的用例沒有步驟，過濾顯示。';
$lang->testtask->caseEmpty         = '選中的用例步驟為空或不符合執行條件，已忽略';

$lang->testtask->beginAndEnd = '起止時間';
$lang->testtask->to          = '至';

$lang->testtask->legendDesc      = '測試單描述';
$lang->testtask->legendReport    = '測試總結';
$lang->testtask->legendBasicInfo = '基本信息';

$lang->testtask->statusList['wait']    = '未開始';
$lang->testtask->statusList['doing']   = '進行中';
$lang->testtask->statusList['done']    = '已關閉';
$lang->testtask->statusList['blocked'] = '被阻塞';

$lang->testtask->priList[1] = '1';
$lang->testtask->priList[2] = '2';
$lang->testtask->priList[3] = '3';
$lang->testtask->priList[4] = '4';

$lang->testtask->unlinkedCases = '未關聯';
$lang->testtask->linkByBuild   = '複製測試單';
$lang->testtask->linkByStory   = "按{$lang->SRCommon}關聯";
$lang->testtask->linkByBug     = '按Bug關聯';
$lang->testtask->linkBySuite   = '按套件關聯';
$lang->testtask->browseBySuite = '按套件查看';
$lang->testtask->passAll       = '全部通過';
$lang->testtask->pass          = '通過';
$lang->testtask->fail          = '失敗';
$lang->testtask->showResult    = '共執行<label class="label primary-pale rounded-full h-3 px-1.5 mx-1">%s</label>次';
$lang->testtask->showFail      = '失敗<label class="label danger-pale rounded-full h-3 px-1.5 mx-1">%s</label>次';
$lang->testtask->runInTask     = ' <strong>%s</strong>，提測構建為 <strong>%s</strong> ';
$lang->testtask->runCaseResult = '，由 <strong>%s</strong> 執行 %s，結果為 <span class="text-%s font-bold">%s</span>。';

$lang->testtask->confirmDelete     = '您確認要刪除該測試單嗎？';
$lang->testtask->confirmUnlinkCase = '您確認要移除該用例嗎？';
$lang->testtask->noticeNoOther     = "該{$lang->productCommon}還沒有其他測試單";
$lang->testtask->noTesttask        = '暫時沒有測試單。';
$lang->testtask->checkLinked       = "請檢查測試單的{$lang->productCommon}是否與{$lang->executionCommon}相關聯";
$lang->testtask->noImportData      = '導入的XML沒有解析出數據。';
$lang->testtask->unitXMLFormat     = '請選擇Junit XML 格式的檔案。';
$lang->testtask->titleOfAuto       = "%s 自動化測試";
$lang->testtask->cannotBeParsed    = '導入的XML檔案內容格式錯誤，無法解析。';
$lang->testtask->finishedDateLess  = '實際完成日期不能小於開始日期%s';
$lang->testtask->finishedDateMore  = '實際完成日期不能大於今天';
$lang->testtask->emptyUnitTip      = '暫時沒有單元測試結果。';

$lang->testtask->assignedToMe  = '指派給我';
$lang->testtask->allCases      = '全部用例';

$lang->testtask->lblCases      = '用例列表';
$lang->testtask->lblUnlinkCase = '移除用例';
$lang->testtask->lblRunCase    = '執行用例';
$lang->testtask->lblResults    = '用例執行結果';

$lang->testtask->placeholder = new stdclass();
$lang->testtask->placeholder->begin = '開始日期';
$lang->testtask->placeholder->end   = '結束日期';

$lang->testtask->mail = new stdclass();
$lang->testtask->mail->create = new stdclass();
$lang->testtask->mail->edit   = new stdclass();
$lang->testtask->mail->close  = new stdclass();
$lang->testtask->mail->create->title = "%s創建了測試單 #%s:%s";
$lang->testtask->mail->edit->title   = "%s編輯了測試單 #%s:%s";
$lang->testtask->mail->close->title  = "%s關閉了測試單 #%s:%s";

$lang->testtask->action = new stdclass();
$lang->testtask->action->testtaskopened  = '$date, 由 <strong>$actor</strong> 創建測試單 <strong>$extra</strong>。' . "\n";
$lang->testtask->action->testtaskstarted = '$date, 由 <strong>$actor</strong> 啟動測試單 <strong>$extra</strong>。' . "\n";
$lang->testtask->action->testtaskclosed  = '$date, 由 <strong>$actor</strong> 完成測試單 <strong>$extra</strong>。' . "\n";

$lang->testtask->unexecuted = '未執行';

/* 統計報表。*/
$lang->testtask->report = new stdclass();
$lang->testtask->report->common = '報表';
$lang->testtask->report->select = '請選擇報表類型';
$lang->testtask->report->create = '生成報表';

$lang->testtask->report->testTaskPerRunResultTip = '共有%s個用例，通過%s個、未執行%s個、失敗%s個';

$lang->testtask->report->charts['testTaskPerRunResult'] = '按用例結果統計';
$lang->testtask->report->charts['testTaskPerType']      = '按用例類型統計';
$lang->testtask->report->charts['testTaskPerModule']    = '按用例模組統計';
$lang->testtask->report->charts['testTaskPerRunner']    = '按用例執行人統計';

$lang->testtask->featureBar['browse']['totalStatus'] = $lang->testtask->totalStatus;
$lang->testtask->featureBar['browse']['myinvolved']  = $lang->testtask->myInvolved;
$lang->testtask->featureBar['browse']['wait']        = $lang->testtask->wait;
$lang->testtask->featureBar['browse']['doing']       = $lang->testtask->testing;
$lang->testtask->featureBar['browse']['blocked']     = $lang->testtask->blocked;
$lang->testtask->featureBar['browse']['done']        = $lang->testtask->done;

$lang->testtask->featureBar['cases']['all']          = $lang->testtask->allCases;
$lang->testtask->featureBar['cases']['assignedtome'] = $lang->testtask->assignedToMe;

$lang->testtask->featureBar['groupcase']['all']          = $lang->testtask->allCases;
$lang->testtask->featureBar['groupcase']['assignedtome'] = $lang->testtask->assignedToMe;

$lang->testtask->featureBar['linkcase']['all']     = $lang->all;
$lang->testtask->featureBar['linkcase']['bystory'] = $lang->testtask->linkByStory;
$lang->testtask->featureBar['linkcase']['bysuite'] = $lang->testtask->linkBySuite;
$lang->testtask->featureBar['linkcase']['bybuild'] = $lang->testtask->linkByBuild;
$lang->testtask->featureBar['linkcase']['bybug']   = $lang->testtask->linkByBug;

$lang->testtask->featureBar['browseunits']['all']       = '全部';
$lang->testtask->featureBar['browseunits']['newest']    = '最近';
$lang->testtask->featureBar['browseunits']['thisWeek']  = '本週';
$lang->testtask->featureBar['browseunits']['lastWeek']  = '上周';
$lang->testtask->featureBar['browseunits']['thisMonth'] = '本月';
$lang->testtask->featureBar['browseunits']['lastMonth'] = '上月';

$lang->testtask->typeList['integrate']   = '整合測試';
$lang->testtask->typeList['system']      = '系統測試';
$lang->testtask->typeList['acceptance']  = '驗收測試';
$lang->testtask->typeList['performance'] = '性能測試';
$lang->testtask->typeList['safety']      = '安全測試';
