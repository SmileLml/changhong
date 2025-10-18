<?php

$config->user->create->requiredFields = 'account,realname,visions,password,scoreStatistic';
$config->user->edit->requiredFields   = 'account,realname,visions,scoreStatistic';

$config->user->form->create['scoreStatistic'] = array('required' => true, 'type' => 'string', 'default' => '');
$config->user->form->edit['scoreStatistic']   = array('required' => true, 'type' => 'string', 'default' => '');

$config->user->list->customBatchCreateFields = 'dept,email,scoreStatistic,gender,commiter,join,skype,qq,dingding,weixin,mobile,slack,whatsapp,phone,address,zipcode';
$config->user->list->customBatchEditFields   = 'dept,email,scoreStatistic,commiter,skype,qq,dingding,weixin,mobile,slack,whatsapp,phone,address,zipcode';

$config->user->form->batchCreate['scoreStatistic'] = array('required' => true, 'type' => 'string', 'default' => '');
$config->user->form->batchEdit['scoreStatistic']   = array('required' => true, 'type' => 'string', 'width' => '100px', 'name' => 'scoreStatistic', 'label' => $lang->user->scoreStatistic, 'control' => array('control' => 'radioList', 'inline' => true), 'default' => '1', 'items' => $lang->user->scoreStatisticList);

$config->user->export->listFields     = explode(',', "dept,role,gender,scoreStatistic,type");
$config->user->export->templateFields = explode(',', "account,realname,dept,gender,scoreStatistic,type,role,join,email,phone,mobile,weixin,qq,address");
$config->user->templateFields         = 'account,realname,dept,gender,scoreStatistic,type,role,join,email,phone,mobile,weixin,qq,address';
$config->user->listFields             = 'dept,role,gender,scoreStatistic,type';

$config->user->list->exportFields  = 'id,account,realname,dept,gender,scoreStatistic,type,group,role,join,email,phone,mobile,weixin,qq,address';
$config->user->list->importFields  = 'id,account,realname,dept,vision,type,group,role,email,gender,scoreStatistic,password,join,phone,mobile,weixin,qq,address';
