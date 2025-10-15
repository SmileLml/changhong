<?php
$lang->group->package->setRatingRules = 'AI评分规则';
$lang->group->package->requestlog     = '请求日志';

$lang->resource->ai->requirementRatingRule  = 'requirementRatingRule';
$lang->resource->ai->storyRatingRule        = 'storyRatingRule';
$lang->resource->ai->taskRatingRule         = 'taskRatingRule';
$lang->resource->ai->bugRatingRule          = 'bugRatingRule';

$lang->resource->ai->promptSetTriggerAction = 'promptSetTriggerAction';

$lang->resource->requestlog = new stdclass();
$lang->resource->requestlog->browse = 'browse';
$lang->moduleOrder[218] = 'requestlog';

$lang->resource->pivot->aiScoreSummary     = 'aiScoreSummary';
$lang->resource->pivot->aiScoreBug         = 'aiScoreBug';
$lang->resource->pivot->aiScoreTask        = 'aiScoreTask';
$lang->resource->pivot->aiScoreRequirement = 'aiScoreRequirement';
$lang->resource->pivot->aiScoreStory       = 'aiScoreStory';
$lang->resource->pivot->aiScoreAvgByMonth  = 'aiScoreAvgByMonth';

$lang->pivot->methodOrder[20] = 'aiScoreSummary';
$lang->pivot->methodOrder[21] = 'aiScoreBug';
$lang->pivot->methodOrder[22] = 'aiScoreTask';
$lang->pivot->methodOrder[23] = 'aiScoreRequirement';
$lang->pivot->methodOrder[24] = 'aiScoreStory';
$lang->pivot->methodOrder[25] = 'aiScoreAvgByMonth';
