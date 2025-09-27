<?php
class myDoc extends doc
{
    /**
     * 创建一个文档库。
     * Create a library.
     *
     * @param  string $type     api|project|product|execution|custom|mine
     * @param  int    $objectID
     * @param  int    $libID
     * @access public
     * @return void
     */
    public function createLib($type = '', $objectID = 0, $libID = 0)
    {
        if($type == 'project')
        {
            $project = $this->doc->fetchByID($objectID, 'project');
            if(!empty($project->isTpl))
            {
                unset($this->lang->doclib->type['api']);
                $this->lang->doc->project = $this->lang->doc->projectTemplate;
            }
        }
        parent::createLib($type, $objectID, $libID);
    }
}
