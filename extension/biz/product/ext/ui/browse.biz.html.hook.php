<?php
namespace zin;
global $app;

$project    = data('project');
$lang       = data('lang');
$browseType = data('browseType');
$productID  = data('productID');
$branchID   = data('branchID');
$storyType  = data('storyType');
$moduleID   = data('moduleID');

if($app->tab == 'project' && isset($project->id))
{
    $button = hasPriv('story', 'report') ? btn(set(array
    (
        'hint'  => $lang->project->reportSettings->common,
        'icon'  => 'bar-chart',
        'class' => 'ghost',
        'url'   => createLink('story', 'report', "productID=$productID&branchID=$branchID&storyType=$storyType&browseType=$browseType&moduleID=$moduleID&chartType=pie&projectID={$project->id}") . '#app=project'
    ))) : null;

    query('#reportBtn')->replaceWith($button);
}
