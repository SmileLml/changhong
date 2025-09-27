<?php
helper::importControl('project');
class myProject extends project
{
    /**
     * 项目模板列表页面。
     * Project template list.
     *
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function template($orderBy = 'id_asc', $recTotal = 0, $recPerPage = 15, $pageID = 1)
    {
        $this->lang->project->common = $this->lang->project->template;

        /* Load pager. */
        $this->app->loadClass('pager', true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $this->view->title          = $this->lang->project->templateList;
        $this->view->templates      = $this->project->getTemplateList('all', $orderBy, $pager);
        $this->view->orderBy        = $orderBy;
        $this->view->users          = $this->loadModel('user')->getPairs('noletter');
        $this->view->workflowGroups = $this->dao->select('id, name')->from(TABLE_WORKFLOWGROUP)->where('type')->eq('project')->fetchPairs();
        $this->view->pager          = $pager;
        $this->display();
    }
}
