<?php
$config->ai->testPrompt->requiredFields = 'name,module,source,purpose';

$config->ai->models = array('openai-gpt35' => 'openai', 'openai-gpt4' => 'openai', 'openai-gpt5mini' => 'openai', 'baidu-ernie' => 'ernie');

$config->ai->openai->model->chat     = array('openai-gpt35' => 'gpt-3.5-turbo', 'openai-gpt4' => 'gpt-4-1106-preview', 'openai-gpt5mini' => 'gpt-5-mini');
$config->ai->openai->model->function = array('openai-gpt35' => 'gpt-3.5-turbo', 'openai-gpt4' => 'gpt-4-1106-preview', 'openai-gpt5mini' => 'gpt-5-mini');

$config->ai->targetForm['other']['score']       = (object)array('m' => 'ai', 'f' => 'score');
$config->ai->targetForm['other']['remarkscore'] = (object)array('m' => 'ai', 'f' => 'remarkscore');

$config->ai->dataSourceExtend = array('remark', 'remarkAll');
unset($config->ai->dataSource['story']);
$config->ai->dataSource['requirement']['requirement'] = array('title', 'spec', 'verify', 'product', 'module', 'pri', 'category', 'estimate');
$config->ai->dataSource['story']['story']             = array('title', 'spec', 'verify', 'product', 'module', 'pri', 'category', 'estimate');

$config->ai->triggerAction = array();
$config->ai->triggerAction['requirement']['create']         = (object)array('m' => 'story', 'f' => 'create');
$config->ai->triggerAction['requirement']['batchcreate']    = (object)array('m' => 'story', 'f' => 'batchcreate');
$config->ai->triggerAction['requirement']['change']         = (object)array('m' => 'story', 'f' => 'change');
$config->ai->triggerAction['requirement']['totask']         = (object)array('m' => 'task', 'f' => 'batchcreate');
$config->ai->triggerAction['requirement']['testcasecreate'] = (object)array('m' => 'testcase', 'f' => 'create');
$config->ai->triggerAction['requirement']['subdivide']      = (object)array('m' => 'story', 'f' => 'batchcreate');
$config->ai->triggerAction['story']['create']               = (object)array('m' => 'story', 'f' => 'create');
$config->ai->triggerAction['story']['batchcreate']          = (object)array('m' => 'story', 'f' => 'batchcreate');
$config->ai->triggerAction['story']['change']               = (object)array('m' => 'story', 'f' => 'change');
$config->ai->triggerAction['story']['totask']               = (object)array('m' => 'task', 'f' => 'batchcreate');
$config->ai->triggerAction['story']['testcasecreate']       = (object)array('m' => 'testcase', 'f' => 'create');
$config->ai->triggerAction['story']['subdivide']            = (object)array('m' => 'story', 'f' => 'batchcreate');
$config->ai->triggerAction['productplan']['edit']           = (object)array('m' => 'productplan', 'f' => 'edit');
$config->ai->triggerAction['productplan']['create']         = (object)array('m' => 'productplan', 'f' => 'create');
$config->ai->triggerAction['project']['programplan/create'] = (object)array('m' => 'programplan', 'f' => 'create');
$config->ai->triggerAction['execution']['batchcreatetask']  = (object)array('m' => 'task', 'f' => 'batchcreate');
$config->ai->triggerAction['task']['edit']                  = (object)array('m' => 'task', 'f' => 'edit');
$config->ai->triggerAction['task']['batchcreate']           = (object)array('m' => 'task', 'f' => 'batchcreate');
$config->ai->triggerAction['testcase']['edit']              = (object)array('m' => 'testcase', 'f' => 'edit');
$config->ai->triggerAction['bug']['edit']                   = (object)array('m' => 'bug', 'f' => 'edit');
$config->ai->triggerAction['bug']['story/create']           = (object)array('m' => 'story', 'f' => 'create');
$config->ai->triggerAction['bug']['testcase/create']        = (object)array('m' => 'testcase', 'f' => 'create');
$config->ai->triggerAction['doc']['edit']                   = (object)array('m' => 'doc', 'f' => 'edit');
