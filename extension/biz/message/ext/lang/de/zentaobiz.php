<?php
global $config;
$lang->message->label->totask               = 'To Task';
$lang->message->label->tostory              = "To {$lang->SRCommon}";
$lang->message->label->totodo               = 'To Todo';
$lang->message->label->tobug                = 'To Bug';
$lang->message->label->toticket             = 'To Ticket';
$lang->message->label->asked                = 'Asked';
$lang->message->label->replied              = 'Replied';
$lang->message->label->reviewed             = 'Reviewed';
$lang->message->label->processed            = 'Processed';
$lang->message->label->deploypublished      = 'Published';
$lang->message->label->recall               = 'Recalled';
$lang->message->label->processedbyticket    = 'Processed By Ticket';
$lang->message->label->processedbystory     = "Processed By {$lang->SRCommon}";
$lang->message->label->processedbytask      = 'Processed By Task';
$lang->message->label->processedbybug       = 'Processed By Bug';
$lang->message->label->processedbytodo      = 'Processed By Todo';
$lang->message->label->processedbydemand    = 'Processed By Demand';
if($config->enableER) $lang->message->label->processedbyepic      = "Processed By {$lang->ERCommon}";
if($config->URAndSR)  $lang->message->label->processedbyuserstory = "Processed By {$lang->URCommon}";

if($config->enableER)$lang->message->label->toepic      = "To {$lang->ERCommon}";
if($config->URAndSR) $lang->message->label->touserstory = "To {$lang->URCommon}";
