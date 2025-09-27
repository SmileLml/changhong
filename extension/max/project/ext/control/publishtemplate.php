<?php
helper::importControl('project');
class myProject extends project
{
    /**
     * 发布项目模板。
     * Publish project template.
     *
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function publishTemplate($projectID)
    {
        $workflowGroup = $this->dao->select('t1.*')->from(TABLE_WORKFLOWGROUP)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.id = t2.workflowGroup')
            ->where('t2.id')->eq($projectID)->fetch();

        if($workflowGroup->status == 'pause' || $workflowGroup->deleted == '1')
        {
            return $this->send(array('result' => 'fail', 'load' => array('alert' => $this->lang->project->cannotPublishTemplate)));
        }

        $this->dao->update(TABLE_PROJECT)->set('status')->eq('doing')->where('id')->eq($projectID)->exec();
        $this->loadModel('action')->create('project', $projectID, 'published');
        $this->sendSuccess(array('load' => true));
    }
}
