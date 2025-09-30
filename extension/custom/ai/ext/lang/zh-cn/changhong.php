<?php
$lang->ai->models->typeList['openai-gpt5mini'] = 'OpenAI / GPT-5-mini';

$lang->ai->models->vendorList->{'openai-gpt5mini'} = array('openaiCompatible' => '自定义');

$lang->ai->ruleTip             = "评分规则如下：\n";
$lang->ai->ruleTipFormat       = "%s：%s\n";
$lang->ai->ruleWeightTip       = "\n评分权重如下：\n";
$lang->ai->ruleWeightTipFormat = "%s：%s%%\n";

$lang->ai->targetForm['other']['common']      = '其他';
$lang->ai->targetForm['other']['score']       = '评分';
$lang->ai->targetForm['other']['remarkscore'] = '备注评分';

$lang->ai->ratingRules = new stdclass();
$lang->ai->ratingRules->common = "AI评分规则";

$lang->ai->ratingRules->category = array();
$lang->ai->ratingRules->category['requirement'] = '用户需求';
$lang->ai->ratingRules->category['story']       = '研发需求';
$lang->ai->ratingRules->category['task']        = '任务';
$lang->ai->ratingRules->category['bug']         = 'Bug';

$lang->ai->ratingRules->remark  =array();
$lang->ai->ratingRules->remark['totalRemark']  = '整体备注';
$lang->ai->ratingRules->remark['singleRemark'] = '单条备注';

$lang->ai->requirementRatingRule = '用户需求评分';
$lang->ai->storyRatingRule       = '研发需求评分';
$lang->ai->taskRatingRule        = '任务评分';
$lang->ai->bugRatingRule         = 'Bug评分';

$lang->ai->formSchema['source'] = new stdclass();
$lang->ai->formSchema['source']->title      = '评分';
$lang->ai->formSchema['source']->type       = 'object';
$lang->ai->formSchema['source']->properties = new stdclass();
$lang->ai->formSchema['source']->required   = array();

$lang->ai->source = new stdclass();
$lang->ai->source->properties = new stdclass();
$lang->ai->source->properties->type        = 'number';
$lang->ai->source->properties->description = "%s评分";

$lang->ai->dataSourceWeightError = '权重之和需等于100% / 0%';

$lang->ai->dataSource['requirement']['common']                  = '用户需求';
$lang->ai->dataSource['requirement']['requirement']['common']   = '用户需求';
$lang->ai->dataSource['requirement']['requirement']['title']    = '需求标题';
$lang->ai->dataSource['requirement']['requirement']['spec']     = '需求描述';
$lang->ai->dataSource['requirement']['requirement']['verify']   = '验收标准';
$lang->ai->dataSource['requirement']['requirement']['product']  = '产品';
$lang->ai->dataSource['requirement']['requirement']['module']   = '模块';
$lang->ai->dataSource['requirement']['requirement']['pri']      = '优先级';
$lang->ai->dataSource['requirement']['requirement']['category'] = '需求类型';
$lang->ai->dataSource['requirement']['requirement']['estimate'] = '预计工时';

$lang->ai->dataSource['story']['common']          = '研发需求';
$lang->ai->dataSource['story']['story']['common'] = '研发需求';

$lang->ai->dataSourceExtend = array();
$lang->ai->dataSourceExtend['remark']    = '最新备注';
$lang->ai->dataSourceExtend['remarkAll'] = '整体备注';

$lang->ai->prompts->modules['story']       = '研发需求';
$lang->ai->prompts->modules['requirement'] = '用户需求';
