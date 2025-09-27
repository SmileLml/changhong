<?php
$config->block->create = new stdclass();
$config->block->create->requiredFields = 'module,code,title';

$config->block->edit = new stdclass();
$config->block->edit->requiredFields = 'module,code,title';

$config->block->form = new stdclass();
$config->block->form->create = array();
$config->block->form->create['module'] = array('type' => 'string', 'required' => false, 'default' => '');
$config->block->form->create['code']   = array('type' => 'string', 'required' => false, 'default' => '');
$config->block->form->create['title']  = array('type' => 'string', 'required' => false, 'default' => '');
$config->block->form->create['width']  = array('type' => 'int',    'required' => false, 'default' => '2');
$config->block->form->create['hidden'] = array('type' => 'int',    'required' => false, 'default' => '0');
$config->block->form->create['params'] = array('type' => 'array',  'required' => false, 'default' => array());
$config->block->form->create['html']   = array('type' => 'string', 'required' => false, 'default' => '', 'control' => 'editor');

$config->block->form->edit = array();
$config->block->form->edit['module'] = array('type' => 'string', 'required' => false, 'default' => '');
$config->block->form->edit['code']   = array('type' => 'string', 'required' => false, 'default' => '');
$config->block->form->edit['title']  = array('type' => 'string', 'required' => false, 'default' => '');
$config->block->form->edit['width']  = array('type' => 'int',    'required' => false, 'default' => '2');
$config->block->form->edit['params'] = array('type' => 'array',  'required' => false, 'default' => array());
$config->block->form->edit['html']   = array('type' => 'string', 'required' => false, 'default' => '', 'control' => 'editor');
