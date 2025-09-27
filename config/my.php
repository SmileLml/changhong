<?php
$config->installed       = true;
$config->debug           = false;
$config->requestType     = 'GET';
$config->timezone        = 'Asia/Shanghai';
$config->db->driver      = 'mysql';
$config->db->host        = '127.0.0.1';
$config->db->port        = '3305';
$config->db->name        = 'changhong';
$config->db->user        = 'root';
$config->db->encoding    = 'utf8mb4';
$config->db->password    = '122112';
$config->db->prefix      = 'zt_';
$config->webRoot         = getWebRoot();
$config->default->lang   = 'zh-cn';
$config->customSession   = true;