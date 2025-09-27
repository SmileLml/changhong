<?php
$config->gogs->form = new stdclass();
$config->gogs->form->create = array();
$config->gogs->form->create['appType']     = array('type' => 'string',   'required' => true,  'default' => 'gogs');
$config->gogs->form->create['type']        = array('type' => 'string',   'required' => true,  'default' => 'external');
$config->gogs->form->create['name']        = array('type' => 'string',   'required' => true, 'default' => '', 'filter' => 'trim');
$config->gogs->form->create['url']         = array('type' => 'string',   'required' => true, 'default' => '', 'filter' => 'trim');
$config->gogs->form->create['token']       = array('type' => 'string',   'required' => true,  'default' => '', 'filter' => 'trim');
$config->gogs->form->create['createdDate'] = array('type' => 'string', 'required' => false, 'default' => helper::now());
$config->gogs->form->create['private']     = array('type' => 'string',   'required' => false, 'default' => uniqid());

$config->gogs->form->edit = array();
$config->gogs->form->edit['name']       = array('type' => 'string', 'required' => true,  'default' => '', 'filter' => 'trim');
$config->gogs->form->edit['url']        = array('type' => 'string', 'required' => true,  'default' => '', 'filter' => 'trim');
$config->gogs->form->edit['token']      = array('type' => 'string', 'required' => true,  'default' => '', 'filter' => 'trim');
$config->gogs->form->edit['editedDate'] = array('type' => 'string', 'required' => false, 'default' => helper::now());
