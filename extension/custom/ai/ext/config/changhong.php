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
