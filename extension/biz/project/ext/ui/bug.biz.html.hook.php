<?php
namespace zin;

$project = data('project');
$lang    = data('lang');

$button = hasPriv('project', 'reportBug') ? btn(set(array
(
    'hint'  => $lang->project->reportSettings->common,
    'icon'  => 'bar-chart',
    'class' => 'ghost',
    'url'   => createLink('project', 'reportBug', 'projectID=' . $project->id)
))) : null;

query('#actionBar')->prepend($button);
