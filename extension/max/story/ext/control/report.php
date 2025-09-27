<?php
helper::importControl('story');
class myStory extends story
{
    /**
     * 查看需求的报告。
     * The report page.
     *
     * @param  int    $productID
     * @param  int    $branchID
     * @param  string $storyType
     * @param  string $browseType
     * @param  int    $moduleID
     * @param  string $chartType
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function report($productID, $branchID, $storyType = 'story', $browseType = 'unclosed', $moduleID = 0, $chartType = 'pie', $projectID = 0)
    {
        if(($this->app->tab != 'execution' && $this->app->tab != 'project') || $storyType != 'story' || !$projectID) return parent::report($productID, $branchID, $storyType, $browseType, $moduleID, $chartType, $projectID);

        $type          = strtolower($browseType);
        $componentList = array();

        $_COOKIE['storyModuleParam'] = $moduleID;
        if($this->app->tab == 'project')
        {
            $projects  = $this->loadModel('project')->getPairs();
            $projectID = $this->project->checkAccess($projectID, $projects);
            $project   = $this->project->getById($projectID);
            $title     = $project->name . $this->lang->hyphen . $this->lang->story->report->common;

            $this->project->setMenu($projectID);
            $storyList = $this->story->getExecutionStories($projectID, $productID, 'order_desc', $type, (string)$moduleID, 'all');

            if(!isset($this->lang->story->report->typeList[$chartType])) $chartType = 'basic';
            $configMethod  = 'build' . ucfirst($chartType) . 'ChartConfig';
            if(method_exists($this->story, $configMethod)) $componentList = $this->story->$configMethod($storyList, $project);
        }
        elseif($this->app->tab == 'execution')
        {
            $executions  = $this->loadModel('execution')->getPairs(0, 'all', "nocode,noprefix,multiple");
            $executionID = $this->execution->checkAccess($projectID, $executions);
            $execution   = $this->execution->getById($executionID);
            $title       = $execution->name . $this->lang->hyphen . $this->lang->story->report->common;

            $this->execution->setMenu($executionID);
            $storyList = $this->story->getExecutionStories($executionID, 0, 'order_desc', $type, (string)$moduleID, 'story,epic,requirement', '');

            if(!isset($this->lang->story->report->typeList[$chartType])) $chartType = 'basic';
            $configMethod  = 'build' . ucfirst($chartType) . 'ChartConfig';
            if(method_exists($this->story, $configMethod)) $componentList = $this->story->$configMethod($storyList, $execution);

            $this->view->executionID  = $executionID;
        }

        $scheme = file_get_contents($this->app->moduleRoot . 'screen/json/screen.json');

        $this->view->title        = $title;
        $this->view->productID    = $productID;
        $this->view->branchID     = $branchID;
        $this->view->projectID    = $projectID;
        $this->view->storyType    = $storyType;
        $this->view->type         = $type;
        $this->view->moduleID     = $moduleID;
        $this->view->chartType    = $chartType;
        $this->view->staticData   = $this->loadModel('screen')->addComponentList(json_decode($scheme), $componentList);
        $this->view->browseType   = $browseType;
        $this->view->filterDetail = $this->processFilterTitle($browseType, (int)$moduleID);
        $this->display('story', 'reportchart');
    }
}
