<?php
$config->group->package->setRatingRules = new stdclass();
$config->group->package->setRatingRules->order  = 2200;
$config->group->package->setRatingRules->subset = 'ai';
$config->group->package->setRatingRules->privs  = array();
$config->group->package->setRatingRules->privs['ai-requirementRatingRule'] = array('edition' => 'open,biz,max,ipd', 'vision' => 'rnd', 'order' => 10, 'depend' => array('admin-index'), 'recommend' => array());
$config->group->package->setRatingRules->privs['ai-storyRatingRule']       = array('edition' => 'open,biz,max,ipd', 'vision' => 'rnd', 'order' => 10, 'depend' => array('admin-index'), 'recommend' => array());
$config->group->package->setRatingRules->privs['ai-taskRatingRule']        = array('edition' => 'open,biz,max,ipd', 'vision' => 'rnd', 'order' => 10, 'depend' => array('admin-index'), 'recommend' => array());
$config->group->package->setRatingRules->privs['ai-bugRatingRule']         = array('edition' => 'open,biz,max,ipd', 'vision' => 'rnd', 'order' => 10, 'depend' => array('admin-index'), 'recommend' => array());

$config->group->package->requestlog = new stdclass();
$config->group->package->requestlog->order  = 20;
$config->group->package->requestlog->subset = 'dev';
$config->group->package->requestlog->privs  = array();
$config->group->package->requestlog->privs['requestlog-browse'] = array('edition' => 'open,biz,max,ipd', 'vision' => 'rnd', 'order' => 10, 'depend' => array('admin-index'), 'recommend' => array());

$config->group->package->managePrompt->privs['ai-promptSetTriggerAction'] = array('edition' => 'biz,max,ipd', 'vision' => 'rnd', 'order' => 65, 'depend' => array('admin-index', 'ai-createPrompt', 'ai-promptAssignRole', 'ai-promptAudit', 'ai-promptExecute', 'ai-promptFinalize', 'ai-prompts', 'ai-promptSelectDataSource', 'ai-promptSetPurpose', 'ai-promptSetTargetForm', 'ai-promptView'), 'recommend' => array());
