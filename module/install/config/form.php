<?php
$config->install->form = new stdclass();

$config->install->form->step2 = array();
$config->install->form->step2['dbDriver']    = array('type' => 'string', 'required' => true, 'default' => 'mysql');
$config->install->form->step2['timezone']    = array('type' => 'string', 'required' => true);
$config->install->form->step2['defaultLang'] = array('type' => 'string', 'required' => true);
$config->install->form->step2['dbHost']      = array('type' => 'string', 'required' => true, 'filter' => 'trim');
$config->install->form->step2['dbPort']      = array('type' => 'string', 'required' => true, 'filter' => 'trim');
$config->install->form->step2['dbEncoding']  = array('type' => 'string', 'required' => true, 'filter' => 'trim');
$config->install->form->step2['dbUser']      = array('type' => 'string', 'required' => true, 'filter' => 'trim');
$config->install->form->step2['dbPassword']  = array('type' => 'string', 'required' => true, 'filter' => 'trim');
$config->install->form->step2['dbName']      = array('type' => 'string', 'required' => true, 'filter' => 'trim');
$config->install->form->step2['dbPrefix']    = array('type' => 'string', 'required' => true, 'filter' => 'trim');
$config->install->form->step2['clearDB']     = array('type' => 'int',    'required' => false, 'default' => 0);

$config->install->form->step4 = array();
$config->install->form->step4['mode'] = array('type' => 'string', 'required' => true);

$config->install->form->step5 = array();
$config->install->form->step5['company']        = array('type' => 'string', 'required' => true, 'filter' => 'trim');
$config->install->form->step5['flow']           = array('type' => 'string', 'required' => true, 'default' => 'full');
$config->install->form->step5['account']        = array('type' => 'string', 'required' => true, 'filter' => 'trim');
$config->install->form->step5['password']       = array('type' => 'string', 'required' => true, 'filter' => 'trim');
$config->install->form->step5['importDemoData'] = array('type' => 'int', 'required' => false, 'default' => 0);
