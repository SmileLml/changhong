<?php
namespace zin;

$productID = data('product.id');
$action    = data('action');
$programID = data('programID');
$loadUrl   = createLink('product', 'edit', "productID={$productID}&action={$action}&extra=workflowGroup={workflowGroup}&programID={$programID}");

query('formGridPanel')->each(function($node) use($loadUrl)
{
    $config = data('config');
    $lang   = data('lang');
    $fields = $node->prop('fields');

    $fields->field('feedback')
        ->label($lang->product->FM)
        ->control('remotepicker')
        ->value(data('product.feedback'));

    $fields->field('ticket')
        ->label($lang->product->TM)
        ->control('remotepicker')
        ->value(data('product.ticket'));

    $fields->field('workflowGroup')
        ->label($lang->product->workflowGroup)
        ->control(array('control' => 'picker', 'beforeChange' => jsRaw("(groupID, afterGroupID) => {if(groupID !== afterGroupID) return zui.Modal.confirm('{$lang->product->confirmChangeWorkflowGroup}')}")))
        ->required(true)
        ->items(data('fields.workflowGroup.options'))
        ->value(data('fields.workflowGroup.default'));

    $fields->field('type')->width(!empty($config->setCode) ? '1/4' : '1/2');
    $fields->field('status')->width(!empty($config->setCode) ? '1/4' : '1/2');

    $fields->moveAfter('workflowGroup', 'name');
    $fields->orders('name,code', 'type,status', 'reviewer,QD,RD,feedback,ticket,desc,acl');
    $fields->fullModeOrders('name,code', 'type,status', 'reviewer,QD,RD,feedback,ticket,desc,acl');

    $fields->autoLoad('workflowGroup');

    $node->setProp('fields', $fields);
    $node->setProp('loadUrl', $loadUrl);
});
