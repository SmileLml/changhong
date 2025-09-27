<?php
namespace zin;

if(hasPriv('report', 'export'))
{
    global $lang;
    $executionID = data('executionID');
    $browseType  = data('browseType');
    $param       = data('param');
    $type        = data('type');

    $exportBtn = btn(set(array('type' => 'primary', 'text' => $lang->export, 'url' => createLink('task', 'exportchart', array('executionID' => $executionID, 'browseType' => $browseType, 'param' => $param, 'type' => $type)), 'data-toggle' => 'modal', 'data-size' => 'sm')));
    query('.detail-header')->append($exportBtn);
}
