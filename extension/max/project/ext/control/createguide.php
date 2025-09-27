<?php
helper::importControl('project');
class myProject extends project
{
    /**
     * 创建项目引导。
     * Project create guide.
     *
     * @param  int    $programID
     * @param  string $from
     * @param  int    $productID
     * @param  int    $branchID
     * @param  int    $charterID
     * @access public
     * @return void
     */
    public function createGuide($programID = 0, $from = 'project', $productID = 0, $branchID = 0, $charterID = 0)
    {
        if($charterID) $this->view->charter = $this->loadModel('charter')->getById($charterID);
        $this->view->charterID = $charterID;
        if(in_array($this->config->edition, array('max', 'ipd'))) $this->view->templates = $this->project->getTemplateList('doing');
        return parent::createGuide($programID, $from, $productID, $branchID);
    }
}
