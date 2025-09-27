<?php
$config->zanode->form = new stdclass();

$config->zanode->form->create = array();
$config->zanode->form->create['hostType']      = array('type' => 'string', 'required' => true);
$config->zanode->form->create['parent']        = array('type' => 'int',    'required' => false, 'default' => 0);
$config->zanode->form->create['name']          = array('type' => 'string', 'required' => true, 'filter' => 'trim');
$config->zanode->form->create['extranet']      = array('type' => 'string', 'required' => false, 'default' => '', 'filter' => 'trim');
$config->zanode->form->create['image']         = array('type' => 'int',    'required' => false, 'default' => 0);
$config->zanode->form->create['cpuCores']      = array('type' => 'int',    'required' => false, 'default' => 0);
$config->zanode->form->create['memory']        = array('type' => 'float',  'required' => true);
$config->zanode->form->create['diskSize']      = array('type' => 'float',  'required' => true);
$config->zanode->form->create['osName']        = array('type' => 'string', 'required' => false, 'default' => '');
$config->zanode->form->create['osNamePre']     = array('type' => 'string', 'required' => false, 'default' => '');
$config->zanode->form->create['osNamePhysics'] = array('type' => 'string', 'required' => false, 'default' => '');
$config->zanode->form->create['desc']          = array('type' => 'string', 'required' => false, 'default' => '', 'control' => 'editor');

$config->zanode->form->edit = array();
$config->zanode->form->edit['name']     = array('type' => 'string', 'required' => false, 'default' => '', 'filter' => 'trim');
$config->zanode->form->edit['extranet'] = array('type' => 'string', 'required' => false, 'default' => '', 'filter' => 'trim');
$config->zanode->form->edit['memory']   = array('type' => 'float',  'required' => false, 'default' => 0);
$config->zanode->form->edit['diskSize'] = array('type' => 'float',  'required' => false, 'default' => 0);
$config->zanode->form->edit['osName']   = array('type' => 'string', 'required' => false, 'default' => '');
$config->zanode->form->edit['desc']     = array('type' => 'string', 'required' => false, 'default' => '', 'control' => 'editor');

$config->zanode->form->createimage = array();
$config->zanode->form->createimage['name'] = array('type' => 'string', 'required' => true,  'filter' => 'trim');
$config->zanode->form->createimage['desc'] = array('type' => 'string', 'required' => false, 'default' => '', 'control' => 'editor');

$config->zanode->form->createsnapshot = array();
$config->zanode->form->createsnapshot['name'] = array('type' => 'string', 'required' => true,  'filter' => 'trim');
$config->zanode->form->createsnapshot['desc'] = array('type' => 'string', 'required' => false, 'default' => '', 'control' => 'editor');

$config->zanode->form->editsnapshot = array();
$config->zanode->form->editsnapshot['name'] = array('type' => 'string', 'required' => true,  'filter' => 'trim');
$config->zanode->form->editsnapshot['desc'] = array('type' => 'string', 'required' => false, 'default' => '', 'control' => 'editor');

$config->zanode->form->ajaxupdateimage = array();
$config->zanode->form->ajaxupdateimage['status'] = array('type' => 'string', 'required' => false, 'default' => '');
$config->zanode->form->ajaxupdateimage['path']   = array('type' => 'string', 'required' => false, 'default' => '');
