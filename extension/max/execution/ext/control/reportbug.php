<?php
class execution extends control
{
    /**
     * 执行下的Bug报表。
     * Report of execution.
     *
     * @param  int    $executionID
     * @param  string $type
     * @access public
     * @return void
     */
    public function reportBug($executionID,  $type = 'basic')
    {
        $this->execution->setMenu($executionID);
        $execution = $this->execution->getByID($executionID);
        $list      = $this->loadModel('bug')->getExecutionBugs($executionID);

        $componentList = array();
        $configMethod  = 'build' . ucfirst($type) . 'BugConfig';
        if(method_exists($this->execution, $configMethod)) $componentList = $this->execution->$configMethod($list, $execution);
        $scheme = file_get_contents($this->app->moduleRoot . 'screen/json/screen.json');
        $title  = $this->lang->execution->report->browseType['bug'] . $this->lang->execution->report->common;

        $this->view->title       = $execution->name . $this->lang->hyphen . $title;
        $this->view->browseType  = 'bug';
        $this->view->type        = $type;
        $this->view->executionID = $executionID;
        $this->view->staticData  = $this->loadModel('screen')->addComponentList(json_decode($scheme), $componentList);
        $this->display();
    }
}
