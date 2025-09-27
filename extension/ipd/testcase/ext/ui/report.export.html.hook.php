<?php
namespace zin;

if(hasPriv('report', 'export'))
{
    global $lang;
    $projectID = data('projectID');
    $type      = data('type');
    $from      = data('from');

    $exportBtn = btn(set(array('type' => 'primary', 'text' => $lang->export, 'url' => createLink('testcase', 'exportchart', "projectID=$projectID&type=$type&from=$from"), 'data-toggle' => 'modal', 'data-size' => 'sm')));
    query('.detail-header')->append($exportBtn);
}
