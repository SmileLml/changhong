<?php
class myDoc extends doc
{
    /**
     * 编辑一个文档库。
     * Edit a library.
     *
     * @param  int    $libID
     * @access public
     * @return void
     */
    public function editLib($libID = 0)
    {
        $lib = $this->doc->fetchByID($libID, 'doclib');
        if($lib->type == 'project')
        {
            $project = $this->doc->fetchByID($lib->project, 'project');
            if(!empty($project->isTpl)) $this->lang->doc->project = $this->lang->doc->projectTemplate;
        }
        parent::editLib($libID);
    }
}
