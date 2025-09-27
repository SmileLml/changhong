<?php
namespace zin;

$programID = data('programID');
$loadUrl   = createLink('product', 'create', "programID={program}&extra=workflowGroup={workflowGroup}");
query('formGridPanel')->each(function($node) use($loadUrl)
{
    $lang   = data('lang');
    $config = data('config');
    $fields = $node->prop('fields');

    $fields->field('feedback')
        ->label($lang->product->FM)
        ->control('remotepicker')
        ->value(data('fields.RD.default'))
        ->foldable();

    $fields->field('ticket')
        ->label($lang->product->TM)
        ->control('remotepicker')
        ->items(data('fields.RD.options'))
        ->value(data('fields.RD.default'))
        ->foldable();

    $fields->field('workflowGroup')
        ->label($lang->product->workflowGroup)
        ->control('picker')
        ->required(true)
        ->items(data('fields.workflowGroup.options'))
        ->value(data('fields.workflowGroup.default'));

    $fields->field('type')->width(!empty($config->setCode) ? '1/2' : '1/4');
    $fields->field('workflowGroup')->width(!empty($config->setCode) ? '1/2' : '1/4');

    $fields->moveAfter('feedback,ticket', 'RD');
    $fields->moveAfter('workflowGroup', 'name');
    $fields->fullModeOrders('RD,feedback,ticket,desc');

    $fields->autoLoad('workflowGroup');

    $node->setProp('fields', $fields);
    $node->setProp('loadUrl', $loadUrl);
});
