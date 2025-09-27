<?php
namespace zin;
global $app;

$lang      = data('lang');
$execution = data('execution');
$reportBtn = btn
(
    setClass('ghost'),
    set::icon('bar-chart'),
    set::hint($lang->execution->report->common),
    set::url(createLink('testcase', 'report', "executionID={$execution->id}")),
    setData('app', 'execution')
);
if(hasPriv('testcase', 'report')) query('#actionBar')->prepend($reportBtn);
