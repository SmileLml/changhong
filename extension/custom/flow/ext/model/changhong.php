<?php

/**
 * Build extend fields for view page use zin setting.
 *
 * @param  array   $fields
 * @param  object  $object
 * @param  string  $position  basic|info
 * @access public
 * @return array
 */
public function buildExtendZinValue($fields, $object = null, $position = 'basic')
{
    $moduleName = $this->app->rawModule;
    $methodName = $this->app->rawMethod;

    if($moduleName == 'projectstory' && $object)
    {
        $moduleName = $object->type;
    }
    elseif($moduleName == 'execution' && $methodName == 'storyview' && $object)
    {
        $moduleName = $object->type;
        $methodName = 'view';
    }

    $groupID = $this->loadModel('workflowgroup')->getGroupIDByData($moduleName, $object);
    $action  = $this->loadModel('workflowaction')->getByModuleAndAction($moduleName, $methodName, $groupID);
    if(empty($action) or $action->extensionType == 'none') return $fields;

    $flow         = $this->loadModel('workflow')->getByModule($moduleName);
    $uiID         = empty($object) ? 0 : $this->loadModel('workflowlayout')->getUIByData($flow->module, $action->action, $object);
    $extendFields = $this->workflowaction->getPageFields($flow->module, $action->action, true, $object, $uiID, $groupID);
    $layouts      = $this->loadModel('workflowlayout')->getFields($moduleName, $methodName, $uiID, $groupID);

    $items = '';
    foreach($layouts as $fieldKey => $fieldName)
    {
        if($fieldKey == 'aiScore' && !isset($extendFields[$fieldKey])) continue;
        $field = $extendFields[$fieldKey];
        if($field->buildin or !$field->show) continue;
        if($position != 'all' and $field->position != $position) continue;

        if($position == 'basic') $items   .= '<div class="datalist-item"><div class="datalist-item-label">' . $field->name . '</div><div class="datalist-item-content">' . $this->getFieldValue($field, $object). '</div></div>';
        if($position == 'info')  $fields[] = zin\setting()->title($field->name)->control('html')->content($this->getFieldValue($field, $object));
    }

    if($items && $position == 'basic') $fields[] = zin\setting()->group('sideExtends')->title($this->lang->extInfo)->control('html')->content("<div class='datalist'>{$items}</div>");

    return $fields;
}
