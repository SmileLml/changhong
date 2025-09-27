<?php
namespace zin;

$lang      = data('lang');
$projectID = data('projectID');
if($projectID)
{
    $reportBtn = btn
    (
        setClass('ghost'),
        set::icon('bar-chart'),
        set::hint($lang->testcase->report->common),
        set::url(createLink('testcase', 'report', "projectID={$projectID}")),
        setData('app', 'project')
    );
    if(hasPriv('testcase', 'report')) query('#actionBar')->prepend($reportBtn);
}
