<?php
/* Field. */
$lang->projectstory->project = "{$lang->projectCommon}ID";
$lang->projectstory->product = "{$lang->productCommon}ID";
$lang->projectstory->story   = "需求ID";
$lang->projectstory->version = "版本";
$lang->projectstory->order   = "排序";

$lang->projectstory->storyCommon = $lang->projectCommon . '需求';
$lang->projectstory->storyList   = $lang->projectCommon . '需求列表';
$lang->projectstory->storyView   = $lang->projectCommon . '需求详情';

$lang->projectstory->common            = "{$lang->projectCommon}需求";
$lang->projectstory->index             = "需求主页";
$lang->projectstory->view              = "需求详情";
$lang->projectstory->story             = "需求列表";
$lang->projectstory->track             = '矩阵';
$lang->projectstory->linkStory         = '关联需求';
$lang->projectstory->unlinkStory       = '移除需求';
$lang->projectstory->report            = '统计报表';
$lang->projectstory->export            = '导出需求';
$lang->projectstory->batchReview       = '批量评审需求';
$lang->projectstory->batchClose        = '批量关闭需求';
$lang->projectstory->batchChangePlan   = '批量修改计划';
$lang->projectstory->batchAssignTo     = '批量指派需求';
$lang->projectstory->batchEdit         = '批量编辑需求';
$lang->projectstory->importToLib       = '导入需求库';
$lang->projectstory->batchImportToLib  = '批量导入需求库';
$lang->projectstory->importCase        = '导入需求';
$lang->projectstory->exportTemplate    = '导出模板';
$lang->projectstory->batchUnlinkStory  = '批量移除需求';
$lang->projectstory->importplanstories = '按计划关联需求';
$lang->projectstory->trackAction       = '跟踪矩阵';
$lang->projectstory->confirm           = '确定';

/* Notice. */
$lang->projectstory->whyNoStories   = "看起来没有需求可以关联。请检查下{$lang->projectCommon}关联的{$lang->productCommon}中有没有需求，而且要确保它们已经审核通过。";
$lang->projectstory->batchUnlinkTip = "其他需求已经移除，如下需求已与该{$lang->projectCommon}下执行相关联，请从执行中移除后再操作。";

$lang->projectstory->featureBar['story']['allstory']  = '全部';
$lang->projectstory->featureBar['story']['unclosed']  = '未关闭';
$lang->projectstory->featureBar['story']['draft']     = '草稿';
$lang->projectstory->featureBar['story']['reviewing'] = '评审中';
$lang->projectstory->featureBar['story']['changing']  = '变更中';
$lang->projectstory->featureBar['story']['more']      = $lang->more;

$lang->projectstory->moreSelects['story']['more']['closed']            = '已关闭';
$lang->projectstory->moreSelects['story']['more']['linkedexecution']   = '已关联' . $lang->execution->common;
$lang->projectstory->moreSelects['story']['more']['unlinkedexecution'] = '未关联' . $lang->execution->common;
