<?php
$config->group->package->setRatingRules = new stdclass();
$config->group->package->setRatingRules->order  = 2200;
$config->group->package->setRatingRules->subset = 'ai';
$config->group->package->setRatingRules->privs  = array();
$config->group->package->setRatingRules->privs['ai-requirementRatingRule'] = array('edition' => 'open,biz,max,ipd', 'vision' => 'rnd', 'order' => 10, 'depend' => array('admin-index'), 'recommend' => array());
$config->group->package->setRatingRules->privs['ai-storyRatingRule']       = array('edition' => 'open,biz,max,ipd', 'vision' => 'rnd', 'order' => 10, 'depend' => array('admin-index'), 'recommend' => array());
$config->group->package->setRatingRules->privs['ai-taskRatingRule']        = array('edition' => 'open,biz,max,ipd', 'vision' => 'rnd', 'order' => 10, 'depend' => array('admin-index'), 'recommend' => array());
$config->group->package->setRatingRules->privs['ai-bugRatingRule']         = array('edition' => 'open,biz,max,ipd', 'vision' => 'rnd', 'order' => 10, 'depend' => array('admin-index'), 'recommend' => array());
