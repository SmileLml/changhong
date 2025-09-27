<?php
class testcase extends control
{
    /**
     * 执行下的用例报表。
     * Report of execution.
     *
     * @param  int    $executionID
     * @param  string $type
     * @access public
     * @return void
     * @param int $projectID
     */
    public function report($projectID, $type = 'basic')
    {
        $tab = $this->app->tab;
        $this->loadModel($tab)->setMenu($projectID);
        $project = $this->$tab->getByID($projectID);
        $list    = $this->testcase->getExecutionCases('all', $projectID);
        if($tab == 'project')
        {
            $productID = 0;
            if(!$project->hasProduct)
            {
                $productPairs = $this->loadModel('product')->getProductPairsByProject($projectID);
                $productID    = key($productPairs);
            }
            $list = $this->testcase->getTestCases($productID, 0, 'all', 0, 0);
        }
        else
        {
            $list = $this->testcase->getExecutionCases('all', $projectID);
            $this->view->executionID = $projectID;
        }

        $componentList = array();
        $configMethod  = 'build' . ucfirst($type) . 'Config';
        if(method_exists($this->testcase, $configMethod)) $componentList = $this->testcase->$configMethod($list, $project);
        $scheme = file_get_contents($this->app->moduleRoot . 'screen/json/screen.json');
        $title  = $this->lang->testcase->common . $this->lang->testcase->report->common;

        $this->view->title      = $project->name . $this->lang->hyphen . $title;
        $this->view->type       = $type;
        $this->view->projectID  = $projectID;
        $this->view->staticData = $this->loadModel('screen')->addComponentList(json_decode($scheme), $componentList);
        $this->display();
    }
}
