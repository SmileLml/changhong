<?php
class task extends control
{
    /**
     * 任务报表。
     * Task report.
     *
     * @param  int    $executionID
     * @param  string $browseType
     * @param  int    $param
     * @param  string $type
     * @access public
     * @return void
     */
    public function report($executionID, $browseType = '', $param = 0, $type = 'basic')
    {
        if($browseType == 'group') $browseType = 'all';

        $execution = $this->loadModel('execution')->getByID($executionID);
        $taskList  = $this->taskZen->getReportTaskList($execution, $browseType, $param);

        $this->lang->task->report->doneNum  = sprintf($this->lang->task->report->doneNum,  zget($this->lang->task->statusList, 'done'));
        $this->lang->task->report->devRate  = sprintf($this->lang->task->report->devRate,  zget($this->lang->task->typeList,   'devel'));
        $this->lang->task->report->testRate = sprintf($this->lang->task->report->testRate, zget($this->lang->task->typeList,   'test'));

        $this->lang->task->report->tips->notFinished = sprintf($this->lang->task->report->tips->notFinished, zget($this->lang->task->statusList, 'done'));
        $this->lang->task->report->tips->doneRate    = sprintf($this->lang->task->report->tips->doneRate,    zget($this->lang->task->statusList, 'done'));
        $this->lang->task->report->tips->devRate     = sprintf($this->lang->task->report->tips->devRate,     zget($this->lang->task->typeList, 'devel'), zget($this->lang->task->statusList, 'done'));
        $this->lang->task->report->tips->testRate    = sprintf($this->lang->task->report->tips->testRate,    zget($this->lang->task->typeList, 'test'),  zget($this->lang->task->statusList, 'done'));

        $componentList = array();
        $configMethod  = 'build' . ucfirst($type) . 'ChartConfig';
        if(method_exists($this->task, $configMethod)) $componentList = $this->task->$configMethod($taskList, $execution);

        $scheme = file_get_contents($this->app->moduleRoot . 'screen/json/screen.json');

        $this->view->title        = $execution->name . $this->lang->hyphen . $this->lang->task->report->common;
        $this->view->type         = $type;
        $this->view->param        = $param;
        $this->view->staticData   = $this->loadModel('screen')->addComponentList(json_decode($scheme), $componentList);
        $this->view->browseType   = $browseType;
        $this->view->executionID  = $executionID;
        $this->view->filterDetail = $this->taskZen->processFilterTitle($browseType, $param);
        $this->display();
    }
}
