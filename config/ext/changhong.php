<?php
$routes['/buglist']  = 'bugsbylist';
$routes['/userinfo'] = 'userinfo';
$config->routes      = $routes;

define('TABLE_AISCORE_RULES', '`' . $config->db->prefix . 'aiscore_rules`');
define('TABLE_AISCORE_WEIGHT', '`' . $config->db->prefix . 'aiscore_weight`');
define('TABLE_AISCORE_RESULT', '`' . $config->db->prefix . 'aiscore_result`');