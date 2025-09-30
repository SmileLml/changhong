<?php
global $lang;

$config->requestlog->search['module'] = 'requestlog';

$config->requestlog->search['fields']['id']          = $lang->requestlog->id;
$config->requestlog->search['fields']['url']         = $lang->requestlog->url;
$config->requestlog->search['fields']['purpose']     = $lang->requestlog->purpose;
$config->requestlog->search['fields']['status']      = $lang->requestlog->status;
$config->requestlog->search['fields']['requestTime'] = $lang->requestlog->requestTime;

$config->requestlog->search['params']['purpose']     = array('operator' => '=', 'control' => 'select', 'values' => array('' => '') + $lang->requestlog->purposeList);
$config->requestlog->search['params']['id']          = array('operator' => '=', 'control' => 'input',  'values' => '');
$config->requestlog->search['params']['status']      = array('operator' => '=', 'control' => 'select', 'values' => array('' => '') + $lang->requestlog->statusList);
$config->requestlog->search['params']['requestTime'] = array('operator' => '=', 'control' => 'input',  'values' => '', 'class' => 'date');
$config->requestlog->search['params']['url']         = array('operator' => 'include', 'control' => 'input',  'values' => '');
