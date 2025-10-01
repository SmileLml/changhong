<?php
public function ratingRules($objectType, $data)
{
    //a($data);exit;
    $rules = new stdclass();
    foreach($data as $field => $value)
    {
        $date = date('Y-m-d H:i:s');
        $hasData = $this->dao->select('*')->from(TABLE_AISCORE_RULES)->where('objectType')->eq($objectType)->andWhere('field')->eq($field)->fetch();
        if(!empty($hasData))
        {
            $this->dao->update(TABLE_AISCORE_RULES)
                ->set('rules')->eq($value)
                ->set('editDate')->eq($date)
                ->where('objectType')->eq($objectType)
                ->andWhere('field')->eq($field)
                ->exec();
        }
        else
        {
            $rules->objectType = $objectType;
            $rules->field      = $field;
            $rules->rules      = $value;
            $rules->editDate   = $date;
            $this->dao->insert(TABLE_AISCORE_RULES)->data($rules)->exec();
        }
    }
    return dao::isError() ? false : $this->dao->lastInsertID();
}

public function getRulesByObjectType($objectType)
{
    $data =  $this->dao->select('*')->from(TABLE_AISCORE_RULES)->where('objectType')->eq($objectType)->fetchAll();
    $rules = new stdclass();
    foreach($data as $rule)
    {
        $rules->{$rule->field} = $rule->rules;
    }
    return $rules;
}

/**
 * Get prompt by module.
 *
 * @access public
 * @return int
 */
public function getPromptByModule()
{
    global $app;

    $triggerControl = $app->moduleName . '.' . $app->methodName;

    return $this->dao->select('*')->from(TABLE_AI_PROMPT)
        ->where('status')->eq('active')
        ->andWhere("FIND_IN_SET('{$triggerControl}', triggerControl)")
        ->orderBy('id_desc')
        ->fetch();
}

/**
 * Update a prompt.
 *
 * @param  object    $prompt
 * @param  object    $originalPrompt  optional, original prompt to compare with and generate action.
 * @access public
 * @return bool
 */
public function updatePrompt($prompt, $originalPrompt = null)
{
    /* Action name to create action record with. */
    $actionType = 'edited';

    /* Compare with original, check what changed. */
    if(!empty($originalPrompt))
    {
        $changedFields = array();
        foreach($prompt as $key => $value)
        {
            if($key == 'weight') continue;
            if($value != $originalPrompt->$key) $changedFields[] = $key;
        }

        /* If only status changed, action is either published or unpublished. */
        if(count($changedFields) == 1 && current($changedFields) == 'status')
        {
            $actionType = $prompt->status == 'draft' ? 'unpublished' : 'published';
        }
        else
        {
            $changes = commonModel::createChanges($originalPrompt, $prompt);
        }
    }

    $prompt->editedDate = helper::now();
    $prompt->editedBy   = $this->app->user->account;

    /* Override uniqueness error message. */
    $this->lang->error->unique = $this->lang->ai->validate->nameNotUnique;
    $weights = !empty($prompt->weight) ? explode(',', trim($prompt->weight, ',')) : array();
    unset($prompt->weight);
    $this->dao->update(TABLE_AI_PROMPT)
        ->data($prompt)
        ->batchCheck($this->config->ai->createprompt->requiredFields, 'notempty')
        ->check('name', 'unique', "`id` != {$prompt->id}")
        ->autoCheck()
        ->where('id')->eq($prompt->id)
        ->exec();

    if(dao::isError()) return false;

    if(!empty($weights))
    {
        $this->dao->delete()->from(TABLE_AISCORE_WEIGHT)->where('promptID')->eq($prompt->id)->exec();
        if(!empty($prompt->source))
        {
            $sources = explode(',', trim($prompt->source, ','));
            $now     = helper::now();
            foreach($sources as $index => $source)
            {
                $weight           = isset($weights[$index]) ? $weights[$index] : '0.00';
                $data             = new stdclass();
                $data->promptID   = $prompt->id;
                $data->field      = $source;
                $data->weight     = $weight;
                $data->createBy   = $this->app->user->account;
                $data->createDate = $now;
                $this->dao->insert(TABLE_AISCORE_WEIGHT)->data($data)->exec();
            }
        }
    }

    $actionId = $this->loadModel('action')->create('prompt', $prompt->id, $actionType);
    if(!empty($changes)) $this->action->logHistory($actionId, $changes);

    return true;
}

/**
 * Get score weights of a prompt.
 *
 * @param  int    $promptID
 * @access public
 * @return object
 */
public function getScoreWeights($promptID)
{
    return $this->dao->select('*')->from(TABLE_AISCORE_WEIGHT)->where('promptID')->eq($promptID)->fetchAll('field');
}

/**
 * Get data source.
 *
 * @access public
 * @return array
 */
public function getDataSource()
{
    $dataSource = $this->config->ai->dataSource;

    if(empty($this->config->ai->dataSourceExtend)) return $dataSource;

    foreach($dataSource as $objectGroupKey => &$objectGroupValue)
    {
        $workflowFields = $this->loadModel('workflowfield')->getFieldPairs($objectGroupKey, 'custom', false, 'order', array(), array('file'));
        $extendData = $this->config->ai->dataSourceExtend;

        if(isset($objectGroupValue[$objectGroupKey]))
        {
            $objectGroupValue[$objectGroupKey] = array_merge($objectGroupValue[$objectGroupKey], $extendData, array_keys($workflowFields));
        }
        else
        {
            $objectGroupValue[$objectGroupKey] = $extendData;
        }

        if(isset($this->lang->ai->dataSourceExtend))
        {
            foreach($extendData as $objectValue)
            {
                if(!isset($this->lang->ai->dataSource[$objectGroupKey][$objectGroupKey]))
                {
                    $this->lang->ai->dataSource[$objectGroupKey][$objectGroupKey] = array();
                }
                $this->lang->ai->dataSource[$objectGroupKey][$objectGroupKey][$objectValue] = $this->lang->ai->dataSourceExtend[$objectValue];
            }
        }

        if(!empty($workflowFields))
        {
            foreach($workflowFields as $fieldKey => $fieldValue)
            {
                if(!isset($this->lang->ai->dataSource[$objectGroupKey][$objectGroupKey]))
                {
                    $this->lang->ai->dataSource[$objectGroupKey][$objectGroupKey] = array();
                }
                $this->lang->ai->dataSource[$objectGroupKey][$objectGroupKey][$fieldKey] = $fieldValue;
            }
        }
    }

    return $dataSource;
}

public function buildRequestLogData($url, $data, $headers, $requestTime, $responseTime, $result)
{
    $logData = array();
    $logData['url']      = $url;
    $logData['clientIP'] =  $_SERVER['REMOTE_ADDR'];
    $logData['requestUser'] = $this->app->user->account;
    $logData['requestTime'] = $requestTime;
    $requeststamp  = strtotime($requestTime);
    $responsestamp = strtotime($responseTime);
    $diffSeconds   = abs($responsestamp - $requeststamp);
    $logData['responseTime'] = $diffSeconds * 1000;
    if(!$result) $logData['status'] = 'fail';
    else $logData['status'] = 'success';
    $logData['params']  = $data;
    $logData['response'] = $result;
    $logData['purpose']  = $this->lang->ai->promptMenu->dropdownTitle;
    return $logData;
}

/**
 * Get the last active step of prompt by id.
 *
 * @param  object $prompt
 * @access public
 * @return string
 */
public function getLastActiveStep($prompt)
{
    if(!empty($prompt))
    {
        if($prompt->status == 'active')     return 'finalize';
        if(!empty($prompt->targetForm))     return 'settargetform';
        if(!empty($prompt->triggerControl)) return 'settriggeraction';
        if(!empty($prompt->purpose))        return 'setpurpose';
        if(!empty($prompt->source))         return 'selectdatasource';
    }
    return 'assignrole';
}

/**
 * Get weight fields of a prompt.
 *
 * @param  string $moduleName
 * @param  string $methodName
 * @access public
 * @return array
 */
public function getWeightFields($moduleName, $methodName)
{
    $prompt = $this->dao->select('id,source')
        ->from(TABLE_AI_PROMPT)
        ->where('status')->eq('active')
        ->andWhere('deleted')->eq('0')
        ->andWhere('triggerControl')->like("%,$moduleName.$methodName,%")
        ->orderBy('id_desc')
        ->fetch();

    if(!$prompt) return array();

    $weightFields = $this->dao->select('field,weight')
        ->from(TABLE_AISCORE_WEIGHT)
        ->where('promptID')->eq($prompt->id)
        ->fetchPairs('field', 'weight');

    return !empty($weightFields) ? $weightFields : array();
}
