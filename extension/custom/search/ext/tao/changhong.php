<?php

/**
 * 设置内置字段的搜索参数。
 * Process buildin fields.
 *
 * @param  string    $module
 * @param  array     $searchConfig
 * @access protected
 * @return array
 */
public function processBuildinFields($module, $searchConfig)
{
    $flowModule = $module;
    if($module == 'projectStory' || $module == 'executionStory' || $module == 'projectstory') $flowModule = 'story';
    if($module == 'projectBuild' || $module == 'executionBuild') $flowModule = 'build';
    if($module == 'projectBug') $flowModule = 'bug';
    if($module == 'executionCase') $flowModule = 'testcase';

    $buildin = false;
    $this->app->loadLang('workflow');
    $this->app->loadConfig('workflow');
    if(!empty($this->config->workflow->buildin))
    {
        foreach($this->config->workflow->buildin->modules as $appModules)
        {
            if(isset($appModules->$flowModule))
            {
                $buildin = true;
                break;
            }
        }
    }

    if(!$buildin) return $searchConfig;

    $groupID  = $this->loadModel('workflowgroup')->getGroupIDBySession();
    $fields   = $this->loadModel('workflowfield')->getList($flowModule, 'searchOrder, `order`, id', $groupID);
    $maxCount = $this->config->maxCount;
    $this->config->maxCount = 0;

    $fieldValues = array();
    $formName    = $module . 'Form';
    if($this->session->$formName)
    {
        foreach($this->session->$formName as $formField)
        {
            $field = zget($formField, 'field', '');
            $value = zget($formField, 'value', '');

            if(empty($field)) continue;
            if($value) $fieldValues[$field][$value] = $value;
        }
    }

    $shouldShowAiScore = $this->loadModel('ai')->checkPromptByModule($module);
    foreach($fields as $field)
    {
        if(isset($field->field) && $field->field === 'aiScore' && !$shouldShowAiScore) continue;
        if($field->canSearch == 0 || $field->buildin) continue;

        if(in_array($field->control, $this->config->workflowfield->optionControls))
        {
            $field->options = $this->workflowfield->getFieldOptions($field, true, zget($fieldValues, $field->field, ''), '', $this->config->flowLimit);
        }

        $searchConfig['fields'][$field->field] = $field->name;
        $searchConfig['params'][$field->field] = $this->loadModel('flow', 'sys')->processSearchParams($field->control, $field->options);
    }
    $this->config->maxCount = $maxCount;

    return $searchConfig;
}
