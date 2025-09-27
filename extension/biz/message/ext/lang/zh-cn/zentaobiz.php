<?php
global $config;
$lang->message->label->totask               = '转任务';
$lang->message->label->tostory              = "转{$lang->SRCommon}";
$lang->message->label->totodo               = '转待办';
$lang->message->label->tobug                = '转Bug';
$lang->message->label->toticket             = '转工单';
$lang->message->label->asked                = '追问';
$lang->message->label->replied              = '回复';
$lang->message->label->reviewed             = '审批';
$lang->message->label->processed            = '已处理';
$lang->message->label->deploypublished      = '上线';
$lang->message->label->recall               = '撤回';
$lang->message->label->processedbyticket    = '由工单处理';
$lang->message->label->processedbystory     = "由{$lang->SRCommon}处理";
$lang->message->label->processedbytask      = '由任务处理';
$lang->message->label->processedbybug       = '由Bug处理';
$lang->message->label->processedbytodo      = '由待办处理';
$lang->message->label->processedbydemand    = '由需求池需求处理';
if($config->enableER) $lang->message->label->processedbyepic      = "由{$lang->ERCommon}处理";
if($config->URAndSR)  $lang->message->label->processedbyuserstory = "由{$lang->URCommon}处理";

if($config->enableER) $lang->message->label->toepic      = "转{$lang->ERCommon}";
if($config->URAndSR)  $lang->message->label->touserstory = "转{$lang->URCommon}";
