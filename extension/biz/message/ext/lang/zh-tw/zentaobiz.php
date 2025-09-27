<?php
global $config;
$lang->message->label->totask               = '轉任務';
$lang->message->label->tostory              = "轉{$lang->SRCommon}";
$lang->message->label->totodo               = '轉待辦';
$lang->message->label->tobug                = '轉Bug';
$lang->message->label->toticket             = '轉工單';
$lang->message->label->asked                = '追問';
$lang->message->label->replied              = '回覆';
$lang->message->label->reviewed             = '審批';
$lang->message->label->processed            = '已處理';
$lang->message->label->deploypublished      = '上線';
$lang->message->label->recall               = '撤回';
$lang->message->label->processedbyticket    = '由工單處理';
$lang->message->label->processedbystory     = "由{$lang->SRCommon}處理";
$lang->message->label->processedbytask      = '由任務處理';
$lang->message->label->processedbybug       = '由Bug處理';
$lang->message->label->processedbytodo      = '由待辦處理';
$lang->message->label->processedbydemand    = '由需求池需求處理';
if($config->enableER) $lang->message->label->processedbyepic      = "由{$lang->ERCommon}處理";
if($config->URAndSR)  $lang->message->label->processedbyuserstory = "由{$lang->URCommon}處理";

if($config->enableER) $lang->message->label->toepic      = "轉{$lang->ERCommon}";
if($config->URAndSR)  $lang->message->label->touserstory = "轉{$lang->URCommon}";
