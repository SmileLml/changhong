<?php
global $app, $config;

$config->stakeholder->form = new stdclass();

$config->stakeholder->form->create['from']        = array('type' => 'string',   'required' => false, 'default' => '');
$config->stakeholder->form->create['user']        = array('type' => 'string',   'required' => false, 'default' => '');
$config->stakeholder->form->create['name']        = array('type' => 'string',   'required' => false, 'default' => '', 'skipRequired' => true);
$config->stakeholder->form->create['objectType']  = array('type' => 'string',   'required' => false, 'default' => $app->tab);
$config->stakeholder->form->create['key']         = array('type' => 'int',      'required' => false, 'default' => 0);
$config->stakeholder->form->create['phone']       = array('type' => 'string',   'required' => false, 'default' => '');
$config->stakeholder->form->create['qq']          = array('type' => 'string',   'required' => false, 'default' => '');
$config->stakeholder->form->create['weixin']      = array('type' => 'string',   'required' => false, 'default' => '');
$config->stakeholder->form->create['email']       = array('type' => 'string',   'required' => false, 'default' => '');
$config->stakeholder->form->create['company']     = array('type' => 'int',      'required' => false, 'default' => 0);
$config->stakeholder->form->create['companyName'] = array('type' => 'string',   'required' => false, 'default' => '');
$config->stakeholder->form->create['newCompany']  = array('type' => 'string',   'required' => false, 'default' => '');
$config->stakeholder->form->create['newUser']     = array('type' => 'string',   'required' => false, 'default' => '');
$config->stakeholder->form->create['nature']      = array('type' => 'string',   'required' => false, 'default' => '', 'control' => 'editor');
$config->stakeholder->form->create['analysis']    = array('type' => 'string',   'required' => false, 'default' => '', 'control' => 'editor');
$config->stakeholder->form->create['strategy']    = array('type' => 'string',   'required' => false, 'default' => '', 'control' => 'editor');

$config->stakeholder->form->edit['key']      = array('type' => 'int',      'required' => false, 'default' => 0);
$config->stakeholder->form->edit['name']     = array('type' => 'string',   'required' => false, 'default' => '');
$config->stakeholder->form->edit['phone']    = array('type' => 'string',   'required' => false, 'default' => '');
$config->stakeholder->form->edit['qq']       = array('type' => 'string',   'required' => false, 'default' => '');
$config->stakeholder->form->edit['weixin']   = array('type' => 'string',   'required' => false, 'default' => '');
$config->stakeholder->form->edit['email']    = array('type' => 'string',   'required' => false, 'default' => '');
$config->stakeholder->form->edit['company']  = array('type' => 'int',      'required' => false, 'default' => 0);
$config->stakeholder->form->edit['nature']   = array('type' => 'string',   'required' => false, 'default' => '', 'control' => 'editor');
$config->stakeholder->form->edit['analysis'] = array('type' => 'string',   'required' => false, 'default' => '', 'control' => 'editor');
$config->stakeholder->form->edit['strategy'] = array('type' => 'string',   'required' => false, 'default' => '', 'control' => 'editor');

$config->stakeholder->form->communicate['comment'] = array('type' => 'string', 'required' => false, 'default' => '', 'control' => 'editor');

$config->stakeholder->form->expect['userID']   = array('type' => 'int',    'required' => false, 'default' => 0);
$config->stakeholder->form->expect['project']  = array('type' => 'int',    'required' => false, 'default' => 0);
$config->stakeholder->form->expect['expect']   = array('type' => 'string', 'required' => true,  'default' => '', 'control' => 'editor');
$config->stakeholder->form->expect['progress'] = array('type' => 'string', 'required' => true,  'default' => '', 'control' => 'editor');
