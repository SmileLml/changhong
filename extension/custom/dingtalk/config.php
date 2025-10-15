<?php
$config->dingtalk->taskWordFields      = 'id,name,project,execution,module,parent,type,pri,status,`desc`';
$config->dingtalk->taskWordBasicFields = 'ID,project,execution,module,parentTask,taskType,pri,status';
$config->dingtalk->bugWordFields       = 'id,title,project,execution,module,product,type,pri,severity,status,resolution,resolvedBuild,steps';
$config->dingtalk->bugWordBasicFields  = 'ID,project,execution,module,product,bugType,pri,severity,status,resolution,resolvedBuild';

$config->dingtalk->tableOptions = array();
$config->dingtalk->tableOptions['borderSize']  = 6;
$config->dingtalk->tableOptions['borderColor'] = '000000';
$config->dingtalk->tableOptions['cellMargin']  = 80;

// 常见的字体映射
$config->dingtalk->fontMap = array();
$config->dingtalk->fontMap['serif']      = 'Times New Roman';
$config->dingtalk->fontMap['sans-serif'] = 'Arial';
$config->dingtalk->fontMap['monospace']  = 'Courier New';
$config->dingtalk->fontMap['cursive']    = 'Comic Sans MS';
$config->dingtalk->fontMap['fantasy']    = 'Impact';

$config->dingtalk->maxImageWidth = 500;