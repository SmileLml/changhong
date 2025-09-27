<?php
if(in_array($config->edition, array('max', 'biz', 'ipd')))
{
    global $lang, $app;
    $app->loadLang('ticket');

    $config->block->ticket = new stdclass();
    $config->block->ticket->dtable = new stdclass();
    $config->block->ticket->dtable->fieldList = array();
    $config->block->ticket->dtable->fieldList['id']       = array('name' => 'id',      'title' => $lang->idAB,            'type' => 'id' ,      'sort' => 'number');
    $config->block->ticket->dtable->fieldList['title']    = array('name' => 'title',   'title' => $lang->ticket->title,   'type' => 'title',    'sort' => true,  'flex' => 1, 'link' => array('module' => 'ticket', 'method' => 'view', 'params' => 'ticketID={id}'));
    $config->block->ticket->dtable->fieldList['product']  = array('name' => 'product', 'title' => $lang->ticket->product, 'type' => 'category', 'sort' => true);
    $config->block->ticket->dtable->fieldList['pri']      = array('name' => 'pri',     'title' => $lang->ticket->priAB,   'type' => 'pri',      'sort' => true);
    $config->block->ticket->dtable->fieldList['status']   = array('name' => 'status',  'title' => $lang->ticket->status,  'type' => 'status',   'sort' => true, 'statusMap' => $lang->ticket->statusList);
    $config->block->ticket->dtable->fieldList['type']     = array('name' => 'type',    'title' => $lang->ticket->type,    'type' => 'category', 'sort' => true, 'map' => $lang->ticket->typeList);

    $config->block->ticket->dtable->short = new stdclass();
    $config->block->ticket->dtable->short->fieldList['id']    = $config->block->ticket->dtable->fieldList['id'];
    $config->block->ticket->dtable->short->fieldList['title'] = $config->block->ticket->dtable->fieldList['title'];
    $config->block->ticket->dtable->short->fieldList['pri']   = $config->block->ticket->dtable->fieldList['pri'];
}
