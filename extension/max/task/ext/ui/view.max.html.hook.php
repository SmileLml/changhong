<?php
namespace zin;

global $lang;
$version  = data('version');
$task     = data('task');
$onItem   = $version ? $version : $task->version;
$taskSpec = data('taskSpec');

$versionItems = array();
for($i = $task->version; $i > 0; $i--)
{
    $versionItems[] = array('value' => $i, 'text' => "#{$i}", 'url' => createLink('task', 'view', "taskID={$taskID}&version={$i}"), 'active' => $onItem == $i);
}

if($version)
{
    $task->name       = $taskSpec->name;
    $task->estStarted = $taskSpec->estStarted;
    $task->deadline   = $taskSpec->deadline;
    $task->delay      = helper::diffDate(helper::today(), $taskSpec->deadline);
}

query('detail')->each(function($node) use($versionItems, $onItem, $task)
{
    $title[] = $task->name;
    $title[] = dropdown
    (
        btn(setClass('ml-2 text-base font-medium ghost bg-gray-200 bg-opacity-50'), "#{$onItem}"),
        set::items($versionItems)
    );
    $node->setProp('object', $task);
    $node->setProp('title', $title);
});
