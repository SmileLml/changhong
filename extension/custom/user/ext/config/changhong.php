<?php

$config->user->create->requiredFields = 'account,realname,visions,password,scoreStatistic';
$config->user->edit->requiredFields   = 'account,realname,visions,scoreStatistic';

$config->user->form->create['scoreStatistic'] = array('required' => true, 'type' => 'string', 'default' => '');
$config->user->form->edit['scoreStatistic']   = array('required' => true, 'type' => 'string', 'default' => '');

$config->user->list->customBatchCreateFields = 'dept,email,scoreStatistic,gender,commiter,join,skype,qq,dingding,weixin,mobile,slack,whatsapp,phone,address,zipcode';
$config->user->list->customBatchEditFields   = 'dept,email,scoreStatistic,commiter,skype,qq,dingding,weixin,mobile,slack,whatsapp,phone,address,zipcode';

$config->user->form->batchCreate['scoreStatistic'] = array('required' => true, 'type' => 'string', 'default' => '');
$config->user->form->batchEdit['scoreStatistic']   = array('required' => true, 'type' => 'string', 'width' => '100px', 'name' => 'scoreStatistic', 'label' => $lang->user->scoreStatistic, 'control' => array('control' => 'radioList', 'inline' => true), 'default' => '1', 'items' => $lang->user->scoreStatisticList);
