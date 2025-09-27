<?php
/**
 * The UI view file of story module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Wang Yidong <yidong@easycorp.ltd>
 * @package     story
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('branchTagOption', $branchTagOption);
jsVar('moduleList', $moduleList);
jsVar('planGroups', $plans);
jsVar('meeting', isset($meetings) ? $meetings : array());
jsVar('researchReports', isset($researchReports) ? $researchReports : array());
jsVar('productStoryList', $productStoryList);

if(!empty($twinsTip)) pageJS("zui.Modal.alert({message: '{$twinsTip}', icon: 'icon-exclamation-sign', iconClass: 'warning-pale rounded-full icon-2x'});\n");

$fields     = $config->story->form->batchEdit;
$showFields = array('module', 'title', 'estimate', 'pri', 'assignedTo', 'status', 'closedBy', 'closedReason', 'keywords');
foreach($fields as $fieldKey => $fieldConfig)
{
    if(!in_array($fieldKey, $showFields)) unset($fields[$fieldKey]);
}

$items = array();
$items['storyIdList'] = array('name' => 'storyIdList', 'label' => '', 'control' => 'hidden', 'hidden' => true);
$items['id']          = array('name' => 'id', 'label' => $lang->idAB, 'control' => 'index', 'width' => '60px');
foreach($fields as $fieldName => $field)
{
    if(isset($field['options']) && $field['options'] == 'users') $field['options'] = $users;
    $items[$fieldName] = array('name' => $fieldName, 'label' => zget($lang->story, $fieldName), 'control' => $field['control'], 'width' => $field['width'], 'required' => $field['required'], 'items' => zget($field, 'options', array()));
}
$items['assignedTo']['ditto'] = true;

if(!$branchProduct) unset($items['branch'], $customFields['branch']);

/* Build form field value for batch edit. */
$fieldNameList = array_keys($items);
$data          = array();
foreach($stories as $storyID => $story)
{
    if(empty($story->pri)) $story->pri = $this->config->story->defaultPriority;
    $data[$storyID] = $story;
    foreach($fieldNameList as $fieldName)
    {
        if($fieldName == 'storyIdList')$data[$storyID]->storyIdList = $story->id;
        if($fieldName == 'status')
        {
            $data[$storyID]->rawStatus  = $story->status;
            $data[$storyID]->$fieldName = $this->processStatus('story', $story);
        }
    }
}

formBatchPanel
(
    set::title($lang->story->batchEdit),
    set::mode('edit'),
    set::items($items),
    set::data(array_values($data)),
    set::customFields(array('list' => array(), 'show' => $showFields, 'key' => 'batchEditFields')),
    set::onRenderRow(jsRaw('renderRowData'))
);

render();
