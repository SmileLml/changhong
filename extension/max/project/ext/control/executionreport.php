<?php
class project extends control
{
    /**
     * 项目下的执行报表。
     * Report of execution.
     *
     * @param  int    $projectID
     * @param  string $type
     * @access public
     * @return void
     */
    public function executionReport($projectID,  $type = 'basic')
    {
        $this->project->setMenu($projectID);
        $project = $this->project->getByID($projectID);

        $componentList = array();
        if($type == 'basic')
        {
            $list         = $this->loadModel('execution')->getList($projectID);
            $configMethod = 'build' . ucfirst($type) . 'ExecutionConfig';
            if(method_exists($this->projectZen, $configMethod)) $componentList = $this->projectZen->$configMethod($list, $project);
        }
        else
        {
            $this->loadModel('task');
            $this->lang->task->report->doneNum  = sprintf($this->lang->task->report->doneNum,  zget($this->lang->task->statusList, 'done'));
            $this->lang->task->report->devRate  = sprintf($this->lang->task->report->devRate,  zget($this->lang->task->typeList,   'devel'));
            $this->lang->task->report->testRate = sprintf($this->lang->task->report->testRate, zget($this->lang->task->typeList,   'test'));

            $this->lang->task->report->tips->notFinished = sprintf($this->lang->task->report->tips->notFinished, zget($this->lang->task->statusList, 'done'));
            $this->lang->task->report->tips->doneRate    = sprintf($this->lang->task->report->tips->doneRate,    zget($this->lang->task->statusList, 'done'));
            $this->lang->task->report->tips->devRate     = sprintf($this->lang->task->report->tips->devRate,     zget($this->lang->task->typeList, 'devel'), zget($this->lang->task->statusList, 'done'));
            $this->lang->task->report->tips->testRate    = sprintf($this->lang->task->report->tips->testRate,    zget($this->lang->task->typeList, 'test'),  zget($this->lang->task->statusList, 'done'));

            $tasks      = $this->task->getProjectTaskList($project->id);
            $taskIdList = array_keys($tasks);
            $teams      = $this->dao->select('t2.task,t1.realname')->from(TABLE_USER)->alias('t1')
                ->leftJoin(TABLE_TASKTEAM)->alias('t2')
                ->on('t1.account = t2.account')
                ->where('t2.task')->in($taskIdList)
                ->fetchAll();

            $teamMembers = array();
            foreach($teams as $user)
            {
                if(!isset($teamMembers[$user->task])) $teamMembers[$user->task] = '';
                $teamMembers[$user->task] .= "$user->realname,";
            }
            foreach($tasks as $task)
            {
                $task->teamMembers = '';
                if($task->mode == 'multi' && !empty($teamMembers[$task->id])) $task->teamMembers = $teamMembers[$task->id];
            }
            $useType       = $type == 'task' ? 'basic' : $type;
            $configMethod  = 'build' . ucfirst($useType) . 'ChartConfig';
            $componentList = $this->task->$configMethod($tasks, $project, 'project');
        }

        $scheme = file_get_contents($this->app->moduleRoot . 'screen/json/screen.json');
        $title  = $this->lang->execution->common . $this->lang->execution->report->common;

        $this->view->title      = $project->name . $this->lang->hyphen . $title;
        $this->view->type       = $type;
        $this->view->projectID  = $projectID;
        $this->view->project    = $project;
        $this->view->staticData = $this->loadModel('screen')->addComponentList(json_decode($scheme), $componentList);
        $this->display();
    }
}
