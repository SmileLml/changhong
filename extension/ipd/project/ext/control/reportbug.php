<?php
class project extends control
{
    /**
     * 项目下的Bug报表。
     * Report of project.
     *
     * @param  int    $projectID
     * @param  string $type
     * @access public
     * @return void
     */
    public function reportBug($projectID, $type = 'basic')
    {
        $this->project->setMenu($projectID);
        $project = $this->project->getByID($projectID);
        $list    = $this->loadModel('bug')->getProjectBugs($projectID);

        $this->loadModel('execution');
        $componentList = array();
        $configMethod  = 'build' . ucfirst($type) . 'BugConfig';
        if(method_exists($this->execution, $configMethod)) $componentList = $this->execution->$configMethod($list, $project, 'project');
        $scheme = file_get_contents($this->app->moduleRoot . 'screen/json/screen.json');
        $title  = $this->lang->bug->common . $this->lang->project->report;

        $this->view->title      = $project->name . $this->lang->hyphen . $title;
        $this->view->browseType = 'bug';
        $this->view->type       = $type;
        $this->view->projectID  = $projectID;
        $this->view->staticData = $this->loadModel('screen')->addComponentList(json_decode($scheme), $componentList);
        $this->display();
    }
}
