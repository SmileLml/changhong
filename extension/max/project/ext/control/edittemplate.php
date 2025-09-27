<?php
helper::importControl('project');
class myProject extends project
{
    /**
     * 编辑项目模板页面。
     * Edit project template.
     *
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function editTemplate($projectID)
    {
        $this->lang->project->name = $this->lang->project->templateName;
        $this->lang->project->desc = $this->lang->project->templateDesc;
        if($_POST)
        {
            $oldProject = $this->project->getByID($projectID);
            $project    = form::data($this->config->project->form->editTemplate)
                ->setDefault('lastEditedBy', $this->app->user->account)
                ->setDefault('lastEditedDate', helper::now())
                ->setIF($this->post->future, 'budget', 0)
                ->setIF($this->post->budget != 0, 'budget', round((float)$this->post->budget, 2))
                ->join('whitelist', ',')
                ->join('auth', ',')
                ->join('storyType', ',')
                ->remove('products,plans,branch,begin,end')
                ->stripTags($this->config->project->editor->edit['id'], $this->config->allowedTags)
                ->get();

            $changes = $this->project->updateTemplate($projectID, $project, $oldProject);

            if(dao::isError()) $this->sendError(dao::getError());

            $actionID = $this->loadModel('action')->create('project', $projectID, 'edited');
            if($changes) $this->action->logHistory($actionID, $changes);

            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => inlink('view', "id=$projectID")));
        }

        $project = $this->project->getByID($projectID);

        $workflowGroups = $this->loadModel('workflowgroup')->getPairs('project', $project->model, $project->hasProduct, 'normal', '0');
        $workflowGroup  = $project->workflowGroup;
        if(!isset($workflowGroups[$workflowGroup]) && $workflowGroup)
        {
            $group = $this->loadModel('workflowgroup')->getByID($workflowGroup);
            if($group) $workflowGroups[$workflowGroup] = $group->name;
        }

        $this->view->title          = $this->lang->project->editTemplate;
        $this->view->project        = $project;
        $this->view->workflowGroups = $workflowGroups;
        $this->view->users          = $this->loadModel('user')->getPairs('noclosed');
        $this->view->budgetUnitList = $this->project->getBudgetUnitList();
        $this->display();
    }
}
