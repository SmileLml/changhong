<?php

/**
 * 简化版的getFields，只获取页面上展示的字段及其配置。
 * Get only show fields of a page.
 *
 * @param  string $module
 * @param  string $action
 * @param  bool   $getRealOptions
 * @param  array  $datas
 * @param  int    $ui
 * @param  int    $groupID
 * @access public
 * @return array
 */
public function getPageFields($module, $action, $getRealOptions = true, $datas = array(), $ui = 0, $groupID = 0)
{
    $fields = parent::getPageFields($module, $action, $getRealOptions, $datas, $ui, $groupID);
    if(empty($fields)) return $fields;
    if($action !== 'view') return $fields;
    $this->loadModel('ai');
    $shouldHideAiScore = in_array($module, $this->config->ai->hideAiScoreFieldForModule);
    $shouldShowAiScore = $this->ai->checkPromptByModule($module);
    foreach($fields as $key => $field)
    {
        $isAiScoreField = ($key === 'aiScore' || (isset($field->field) && $field->field === 'aiScore'));
        if($isAiScoreField && ($shouldHideAiScore || !$shouldShowAiScore)) unset($fields[$key]);
    }

    return $fields;
}
