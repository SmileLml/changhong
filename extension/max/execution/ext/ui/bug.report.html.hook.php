<?php
namespace zin;
global $app;

$lang           = data('lang');
$execution      = data('execution');
$moduleID       = data('moduleID');
$browseType     = data('browseType');
$param          = data('param');
$productID      = data('productID');
$defaultProduct = data('defaultProduct');
$canExportBug   = hasPriv('bug', 'export');
$canCreateBug   = hasPriv('bug', 'create') && common::canModify('execution', $execution);

if($canExportBug) $exportItem = array
(
    'icon'        => 'export',
    'class'       => 'ghost',
    'text'        => $lang->export,
    'data-toggle' => 'modal',
    'url'         => createLink('bug', 'export', "productID={$productID}&browseType=&executionID={$execution->id}")
);
if($canCreateBug) $createItem = array
(
    'icon'     => 'plus',
    'class'    => 'primary createBug-btn',
    'text'     => $lang->bug->create,
    'data-app' => 'execution',
    'url'      => createLink('bug', 'create', "productID={$defaultProduct}&branch=0&extras=executionID={$execution->id},moduleID={$moduleID}")
);

$toolbar = toolbar
(
    hasPriv('execution', 'reportBug') ? item(set(array
    (
        'icon'     => 'bar-chart',
        'class'    => 'ghost',
        'hint'     => $lang->bug->report->common,
        'data-app' => 'execution',
        'url'      => createLink('execution', 'reportbug', "executionID={$execution->id}")
    ))) : null,
    !empty($canExportBug) ? item(set($exportItem)) : null,
    !empty($createItem) ? item(set($createItem)) : null
);

query('#actionBar')->replaceWith($toolbar);
pageJS('$(function(){$("#mainMenu .toolbar").prop("id", "actionBar"); });');
