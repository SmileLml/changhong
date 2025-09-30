<?php
$routes['/buglist']  = 'bugsbylist';
$routes['/userinfo'] = 'userinfo';
$config->routes = $routes;

define('TABLE_SOURCE_RULES',  '`' . $config->db->prefix . 'source_rules`');
define('TABLE_SOURCE_WEIGHT', '`' . $config->db->prefix . 'source_weight`');