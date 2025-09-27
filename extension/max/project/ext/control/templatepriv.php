<?php
helper::importControl('project');
class myProject extends project
{
    /**
     * 设置项目模板编辑和使用权限。
     * Set template priv.
     *
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function templatePriv($projectID)
    {
        if(!empty($_POST))
        {
            $postData = form::data($this->config->project->form->templatePriv)->get();

            $template = new stdclass();
            $template->tplAcl       = $postData->acl;
            $template->tplWhiteList = $postData->whitelist;
            $this->dao->update(TABLE_PROJECT)->data($template)->where('id')->eq($projectID)->exec();

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            return $this->sendSuccess(array('closeModal' => true, 'load' => true));
        }
        $this->view->title   = $this->lang->project->templatePriv;
        $this->view->project = $this->project->fetchByID($projectID);
        $this->display();
    }
}
