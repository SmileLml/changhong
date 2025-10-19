<?php

public function aiScoreBug($begin = 0, $end = 0, $searchDept = 0, $searchUser = '', $searchTitle = '', $recTotal = 0, $recPerPage = 20, $pageID = 1)
{
    $begin = $begin ? date('Y-m-d', strtotime($begin)) : date('Y-m-d', strtotime(date('Y-m',time()) . '-01 00:00:01'));
    $end   = $end   ? date('Y-m-d', strtotime($end))   : date('Y-m-d', strtotime('now'));

    $this->loadModel('bug');
    $this->app->loadClass('pager', $static = true);
    $pager = new pager($recTotal, $recPerPage, $pageID);
    
    list($bugs, $fields) = $this->pivot->getAiScoreBug($begin, $end, $searchDept, $searchUser, $searchTitle, $pager);

    $this->view->title       = $this->lang->pivot->aiScoreBug;
    $this->view->pivotName   = $this->lang->pivot->aiScoreBug;
    $this->view->users       = array('' => '') + $this->loadModel('user')->getPairs('noletter|noclosed');
    $this->view->depts       = $this->loadModel('dept')->getOptionMenu();
    $this->view->begin       = $begin;
    $this->view->end         = $end;
    $this->view->searchDept  = $searchDept;
    $this->view->searchUser  = $searchUser;
    $this->view->searchTitle = $searchTitle;
    $this->view->pager       = $pager;
    $this->view->bugs        = $bugs;
    $this->view->fields      = $fields;
    $this->view->submenu     = 'ai';
    $this->view->currentMenu = 'aiScoreBug';
}
