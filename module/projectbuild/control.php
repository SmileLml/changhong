<?php
/**
 * The control file of projectBuild module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     projectBuild
 * @version     $Id: control.php 5094 2013-07-10 08:46:15Z chencongzhi520@gmail.com $
 * @link        https://www.zentao.net
 */
class projectBuild extends control
{
    /**
     * 获取项目的版本列表。
     * Browse builds of a project.
     *
     * @param  int    $projectID
     * @param  string $type      all|product|bysearch
     * @param  int    $param
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function browse($projectID = 0, $type = 'all', $param = 0, $orderBy = 't1.date_desc,t1.id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        echo $this->fetch('project', 'build', "projectID={$projectID}&type={$type}&param={$param}&orderBy={$orderBy}&recTotal={$recTotal}&recPerPage={$recPerPage}&pageID={$pageID}");
    }

    /**
     * 创建项目版本。
     * Create a build for project.
     *
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function create($projectID = 0)
    {
        $executionID = 0;
        $project     = $this->loadModel('project')->fetchById($projectID);

        if($project->model == 'waterfall') $this->lang->projectbuild->execution = $this->lang->project->stage;

        if(strpos('stage,sprint,kanban', $project->type) !== false)
        {
            $executionID = $projectID;
            $projectID   = $project->project;
        }
        echo $this->fetch('build', 'create', "executionID=$executionID&productID=0&projectID=$projectID");
    }

    /**
     * 编辑项目版本。
     * Edit a build for project.
     *
     * @param  int    $buildID
     * @access public
     * @return void
     */
    public function edit($buildID)
    {
        echo $this->fetch('build', 'edit', "buildID=$buildID");
    }

    /**
     * 查看项目版本。
     * View a build for project.
     *
     * @param  int    $buildID
     * @param  string $type
     * @param  string $link
     * @param  string $param
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function view($buildID, $type = 'story', $link = 'false', $param = '', $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 100, $pageID = 1)
    {
        echo $this->fetch('build', 'view', "buildID=$buildID&type=$type&link=$link&param=$param&orderBy=$orderBy&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID");
    }

    /**
     * 删除项目版本。
     * Delete a build for project.
     *
     * @param  int    $buildID
     * @access public
     * @return void
     */
    public function delete($buildID)
    {
        $this->locate($this->createLink('build', 'delete', "buildID={$buildID}&from=project"));
    }

    /**
     * 项目版本关联需求。
     * Link stories.
     *
     * @param  int    $buildID
     * @param  string $browseType
     * @param  int    $param
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function linkStory($buildID = 0, $browseType = '', $param = 0, $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 100, $pageID = 1)
    {
        echo $this->fetch('build', 'linkStory', "buildID=$buildID&browseType=$browseType&param=$param&orderBy=$orderBy&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID");
    }

    /**
     * 移除关联的需求。
     * Unlink story.
     *
     * @param  int    $buildID
     * @param  int    $storyID
     * @access public
     * @return void
     */
    public function unlinkStory($buildID, $storyID)
    {
        echo $this->fetch('build', 'unlinkStory', "buildID=$buildID&storyID=$storyID");
    }

    /**
     * 批量移除关联的需求。
     * Batch unlink story.
     *
     * @param  int    $buildID
     * @access public
     * @return void
     */
    public function batchUnlinkStory($buildID)
    {
        echo $this->fetch('build', 'batchUnlinkStory', "buildID=$buildID");
    }

    /**
     * 项目版本关联Bug。
     * Link bugs.
     *
     * @param  int    $buildID
     * @param  string $browseType
     * @param  int    $param
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function linkBug($buildID = 0, $browseType = '', $param = 0, $recTotal = 0, $recPerPage = 100, $pageID = 1)
    {
        echo $this->fetch('build', 'linkBug', "buildID=$buildID&browseType=$browseType&param=$param&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID");
    }

    /**
     * 移除关联的Bug。
     * Unlink bug.
     *
     * @param  int    $buildID
     * @param  int    $bugID
     * @access public
     * @return void
     */
    public function unlinkBug($buildID, $bugID)
    {
        echo $this->fetch('build', 'unlinkBug', "buildID=$buildID&bugID=$bugID");
    }

    /**
     * 批量移除关联的Bug。
     * Batch unlink bug.
     *
     * @param  int    $buildID
     * @access public
     * @return void
     */
    public function batchUnlinkBug($buildID)
    {
        echo $this->fetch('build', 'batchUnlinkBug', "buildID=$buildID");
    }
}
