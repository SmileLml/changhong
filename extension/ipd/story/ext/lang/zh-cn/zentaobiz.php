<?php
global $app;

$lang->story->feedback    = '反馈';
$lang->story->docs        = '相关文档';
$lang->story->docVersions = '文档版本号';
$lang->story->docSyncTips = '该文档存在最新版本';

$lang->story->report->typeList['basic']    = '基本统计';
$lang->story->report->typeList['progress'] = '进度分析';

$lang->story->report->tpl = new stdclass();
$lang->story->report->tpl->filter  = '列表筛选条件：';
$lang->story->report->tpl->feature = '%s的需求';
$lang->story->report->tpl->search  = '%s%s%s';
$lang->story->report->tpl->multi   = '（%s）%s（%s）';

$lang->story->report->tips = new stdclass();
$lang->story->report->tips->changedNum    = '需求变更日期晚于%s实际开始日期，早于%s实际关闭日期，且状态不为变更中和评审中的需求';
$lang->story->report->tips->devRate       = '按条目统计的需求研发完毕率：研发完毕需求条目数÷需求条目数*100%。';
$lang->story->report->tips->devScaleRate  = '按规模统计的需求研发完毕率：研发完毕需求规模数÷需求规模数*100%。';
$lang->story->report->tips->testRate      = '按条目统计的需求测试完毕率：测试完毕需求条目数÷需求条目数*100%。';
$lang->story->report->tips->testScaleRate = '按规模统计的需求测试完毕率：测试完毕需求规模数÷需求规模数*100%。';
$lang->story->report->tips->doneRate      = '按条目统计的需求完成率：已完成需求条目数÷需求条目数*100%。';
$lang->story->report->tips->doneScaleRate = '按规模统计的需求完成率：已完成需求规模数÷需求规模数*100%。';
$lang->story->report->tips->productMap    = '最多展示二级模块';

$lang->story->report->notice        = '请选择统计内容';
$lang->story->report->storyNum      = '需求条目数';
$lang->story->report->storyScale    = '需求规模数';
$lang->story->report->devNum        = '研发完毕需求条目数';
$lang->story->report->devScale      = '研发完毕需求规模数';
$lang->story->report->testNum       = '测试完毕需求条目数';
$lang->story->report->testScale     = '测试完毕需求规模数';
$lang->story->report->doneNum       = '已完成需求条目数';
$lang->story->report->doneScale     = '已完成需求规模数';
$lang->story->report->closedNum     = '已关闭需求条目数';
$lang->story->report->closedScale   = '已关闭需求规模数';
$lang->story->report->changedNum    = '变更的需求条目数';
$lang->story->report->changedScale  = '变更的需求规模数';
$lang->story->report->statusMap     = '需求状态分布';
$lang->story->report->stageMap      = '需求阶段分布';
$lang->story->report->productMap    = '需求来源产品模块分布';
$lang->story->report->sourceMap     = '需求来源分布';
$lang->story->report->moduleMap     = '需求所在一级模块分布';
$lang->story->report->priMap        = '需求优先级分布';
$lang->story->report->categoryMap   = '需求所属类别分布';
$lang->story->report->userMap       = '需求由谁创建分布';
$lang->story->report->devRate       = '按条目统计的需求研发完毕率';
$lang->story->report->devScaleRate  = '按规模统计的需求研发完毕率';
$lang->story->report->testRate      = '按条目统计的需求测试完毕率';
$lang->story->report->testScaleRate = '按规模统计的需求测试完毕率';
$lang->story->report->doneRate      = '按条目统计的需求完成率';
$lang->story->report->doneScaleRate = '按规模统计的需求完成率';
