<?php
helper::importControl('project');
class myProject extends project
{
    /**
     * 停用项目模板。
     * Disable project template.
     *
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function disableTemplate($projectID)
    {
        $this->dao->update(TABLE_PROJECT)->set('status')->eq('closed')->where('id')->eq($projectID)->exec();
        $this->loadModel('action')->create('project', $projectID, 'disabled');
        $this->sendSuccess(array('load' => true));
    }
}
