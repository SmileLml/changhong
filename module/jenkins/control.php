<?php
/**
 * The control file of jenkins module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chenqi <chenqi@cnezsoft.com>
 * @package     product
 * @version     $Id: ${FILE_NAME} 5144 2020/1/8 8:10 下午 chenqi@cnezsoft.com $
 * @link        https://www.zentao.net
 */
class jenkins extends control
{
    /**
     * Jenkins 模块初始化。
     * jenkins constructor.
     *
     * @param string $moduleName
     * @param string $methodName
     */
    public function __construct($moduleName = '', $methodName = '')
    {
        parent::__construct($moduleName, $methodName);

        if(stripos($this->methodName, 'ajax') === false && !commonModel::hasPriv('space', 'browse')) $this->loadModel('common')->deny('space', 'browse', false);
        $this->loadModel('ci')->setMenu();
    }

    /**
     * 创建一个jenkins服务器。
     * Create a jenkins.
     *
     * @access public
     * @return void
     */
    public function create()
    {
        if($_POST)
        {
            $jenkins = form::data($this->config->jenkins->form->create)
                ->add('createdBy', $this->app->user->account)
                ->get();
            $this->jenkinsZen->checkTokenAccess($jenkins->url, $jenkins->account, $jenkins->password, $jenkins->token);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $jenkinsID = $this->loadModel('pipeline')->create($jenkins);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->loadModel('action')->create('jenkins', $jenkinsID, 'created');
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->createLink('space', 'browse')));
        }
    }

    /**
     * 编辑一个Jenkins服务器。
     * Edit a jenkins.
     *
     * @param  int    $jenkinsID
     * @access public
     * @return void
     */
    public function edit($jenkinsID)
    {
        $jenkins = $this->loadModel('pipeline')->getByID($jenkinsID);
        if($_POST)
        {
            $jenkins = form::data($this->config->jenkins->form->edit)
                ->add('editedBy', $this->app->user->account)
                ->get();
            $this->jenkinsZen->checkTokenAccess($jenkins->url, $jenkins->account, $jenkins->password, $jenkins->token);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->pipeline->update($jenkinsID, $jenkins);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $newJenkins = $this->pipeline->getByID($jenkinsID);
            $actionID   = $this->loadModel('action')->create('jenkins', $jenkinsID, 'edited');
            $changes    = common::createChanges($jenkins, $newJenkins);
            $this->action->logHistory($actionID, $changes);
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true, 'closeModal' => true));
        }

        $this->view->title   = $this->lang->jenkins->common . $this->lang->hyphen . $this->lang->jenkins->edit;
        $this->view->jenkins = $jenkins;
        $this->display();
    }

    /**
     * 删除一条jenkins数据。
     * Delete a jenkins.
     *
     * @param  int    $jenkinsID
     * @access public
     * @return void
     */
    public function delete($jenkinsID)
    {
        $jobs = $this->jenkins->getJobPairs($jenkinsID);
        if(!empty($jobs)) return $this->sendError($this->lang->jenkins->error->linkedJob, true);

        $this->jenkins->delete(TABLE_PIPELINE, $jenkinsID);
        return $this->send(array('result' => 'success', 'load' => $this->createLink('space', 'browse')));
    }

    /**
     * 获取Jenkins任务列表。
     * AJAX: Get jenkins tasks.
     *
     * @param  int    $jenkinsID
     * @access public
     * @return void
     */
    public function ajaxGetJenkinsTasks($jenkinsID = 0)
    {
        $tasks = array();
        if($jenkinsID) $tasks = $this->jenkins->getTasks($jenkinsID, 3);

        $this->view->tasks = $this->jenkinsZen->buildTree($tasks);
        $this->display();
    }
}
