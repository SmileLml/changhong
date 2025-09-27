<?php
$config->custom->notSetMethods[] = 'storyGrade';
$config->custom->notSetMethods[] = 'epicGrade';
$config->custom->notSetMethods[] = 'requirementGrade';

$config->custom->relateObjectList['demand']   = $lang->demand->common;
$config->custom->relateObjectFields['demand'] = array('id', 'relation', 'pri', 'title', 'pool', 'createdBy', 'assignedTo', 'status');

$config->custom->form->setCharterInfo['type'] = array('type' => 'string', 'required' => true);

$config->custom->fieldList['project']['create'] = 'charter,' . $config->custom->fieldList['project']['create'];
$config->custom->fieldList['project']['edit']   = 'charter,' . $config->custom->fieldList['project']['edit'];
