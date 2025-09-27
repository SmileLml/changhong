<?php
/**
 * Save copy project.
 *
 * @param  int    $copyProjectID
 * @param  string $model
 * @param  object $executions
 * @param  string $copyFrom
 * @access public
 * @return string
 */
public function saveCopyProject($copyProjectID, $model = 'scrum', $executions = array(), $copyFrom = '')
{
    return $this->loadExtension('zentaomax')->saveCopyProject($copyProjectID, $model, $executions, $copyFrom);
}

/**
 * Check create.
 *
 * @access public
 * @return bool
 */
public function checkCreate($copyProjectID = 0)
{
    return $this->loadExtension('zentaomax')->checkCreate($copyProjectID);
}

/**
 * check execution.
 *
 * @param array  $executions
 * @param int    $projectID
 * @param string $model
 * @access public
 * @return void
 */
public function checkExecution($executions, $projectID = 0, $model = 'scrum')
{
    return $this->loadExtension('zentaomax')->checkExecution($executions, $projectID, $model);
}

/**
 * Save process.
 *
 * @param int     $copyProjectID
 * @param int     $executionID
 * @param int     $lastExecutionID
 * @param string  $model
 * @access public
 * @return void
 */
public function saveProcess($copyProjectID, $projectID, $executionID, $lastExecutionID, $model)
{
    return $this->loadExtension('zentaomax')->saveProcess($copyProjectID, $projectID, $executionID, $lastExecutionID, $model);
}

/**
 * 复制项目的任务关系。
 * Copy project task relations.
 *
 * @param  int    $copyProjectID
 * @param  int    $projectID
 * @param  array  $executionIdList
 * @param  array  $taskIdList
 * @access public
 * @return bool
 */
public function copyProjectTaskRelations($copyProjectID, $projectID, $executionIdList, $taskIdList)
{
    return $this->loadExtension('zentaomax')->copyProjectTaskRelations($copyProjectID, $projectID, $executionIdList, $taskIdList);
}

/**
 * 复制项目的审批设置。
 * Copy project approval flow objects.
 *
 * @param  int    $copyProjectID
 * @param  int    $projectID
 * @access public
 * @return bool
 */
public function copyProjectApprovalFlow($copyProjectID, $projectID)
{
    return $this->loadExtension('zentaomax')->copyProjectApprovalFlow($copyProjectID, $projectID);
}

/**
 * 复制迭代的模块。
 * Copy execution Module.
 *
 * @param  int    $executionID
 * @param  int    $lastExecutionID
 * @access public
 * @return array
 */
public function copyExecutionTaskModule($executionID, $lastExecutionID)
{
    return $this->loadExtension('zentaomax')->copyExecutionTaskModule($executionID, $lastExecutionID);
}

/**
 * 复制项目的评审点。
 * Copy project review point.
 *
 * @param  int    $copyProjectID
 * @param  int    $projectID
 * @access public
 * @return bool
 */
public function copyProjectReviewPoint($copyProjectID, $projectID)
{
    return $this->loadExtension('zentaomax')->copyProjectReviewPoint($copyProjectID, $projectID);
}

/**
 * Save QA.
 *
 * @param int  $copyProjectID
 * @param int  $projectID
 * @param int  $executionID
 * @param int  $lastExecutionID
 * @access public
 * @return void
 */
public function saveQA($copyProjectID, $projectID, $executionID, $lastExecutionID)
{
    return $this->loadExtension('zentaomax')->saveQA($copyProjectID, $projectID, $executionID, $lastExecutionID);
}

/**
 * Save task.
 *
 * @param  int    $copyProjectID
 * @param  int    $projectID
 * @param  int    $executionID
 * @param  int    $lastExecutionID
 * @param  array  $moduleList
 * @param  string $copyFrom
 * @access public
 * @return void
 */
public function saveTask($copyProjectID, $projectID, $executionID, $lastExecutionID, $moduleList, $copyFrom = '')
{
    return $this->loadExtension('zentaomax')->saveTask($copyProjectID, $projectID, $executionID, $lastExecutionID, $copyFrom);
}

/**
 * Save execution doc lib.
 *
 * @param int  $executionID
 * @param int  $lastExecutionID
 * @access public
 * @return void
 */
public function saveExecutionDocLib($executionID, $lastExecutionID)
{
    return $this->loadExtension('zentaomax')->saveExecutionDocLib($executionID, $lastExecutionID);
}

/**
 * Save project doc lib.
 *
 * @param int  $copyProjectID
 * @param int  $projectID
 * @access public
 * @return void
 */
public function saveProjectDocLib($copyProjectID, $projectID)
{
    return $this->loadExtension('zentaomax')->saveProjectDocLib($copyProjectID, $projectID);
}

/**
 * Save team.
 *
 * @param int     $copyObjectID
 * @param int     $objectID
 * @param string  $type
 * @access public
 * @return void
 */
public function saveTeam($copyObjectID, $objectID, $type = 'project')
{
    return $this->loadExtension('zentaomax')->saveTask($copyObjectID, $objectID, $type);
}

/**
 * Save stakeholder.
 *
 * @param int  $copyProjectID
 * @param int  $projectID
 * @access public
 * @return void
 */
public function saveStakeholder($copyProjectID, $projectID)
{
    return $this->loadExtension('zentaomax')->saveStakeholder($copyProjectID, $projectID);
}

/**
 * Save group.
 *
 * @param int  $copyProjectID
 * @param int  $projectID
 * @access public
 * @return void
 */
public function saveGroup($copyProjectID, $projectID)
{
    return $this->loadExtension('zentaomax')->saveGroup($copyProjectID, $projectID);
}

/**
 * Save RD kanban.
 *
 * @param  int    $execution
 * @param  int    $lastExecutionID
 * @access public
 * @return void
 */
public function saveKanban($execuitonID, $lastExecutionID)
{
    return $this->loadExtension('zentaomax')->saveKanban($execuitonID, $lastExecutionID);
}

/**
 * Set menu of project module.
 *
 * @param  int    $projectID
 * @access public
 * @return int|false
 */
public function setMenu($projectID)
{
    return $this->loadExtension('zentaomax')->setMenu($projectID);
}

/**
 * 检查名称唯一性.
 * Check name unique.
 *
 * @param  array  $names
 * @access public
 * @return bool
 */
public function checkNameUnique($names)
{
    return $this->loadExtension('zentaomax')->checkNameUnique($names);
}

/**
 * 获取项目或执行的交付物。
 * Get project or execution deliverable.
 *
 * @param  int    $projectID
 * @param  int    $executionID
 * @param  int    $groupID
 * @param  string $projectType
 * @param  string $projectModel
 * @param  string $method
 * @access public
 * @return array
 */
public function getProjectDeliverable($projectID, $executionID, $groupID = 0, $projectType = 'project', $projectModel = 'scrum', $method = 'whenCreated')
{
    return $this->loadExtension('zentaomax')->getProjectDeliverable($projectID, $executionID, $groupID, $projectType, $projectModel, $method);
}

/**
 * 创建一个项目。
 * Create a project.
 *
 * @param  object   $project
 * @param  object   $postData
 * @access public
 * @return int|bool
 */
public function create($project, $postData)
{
    return $this->loadExtension('zentaomax')->create($project, $postData);
}

/**
 * 关闭项目并更改其状态
 * Close project and update status.
 *
 * @param  int    $projectID
 * @param  object $project
 *
 * @access public
 * @return array|false
 */
public function close($projectID, $project)
{
    return $this->loadExtension('zentaomax')->close($projectID, $project);
}

/**
 *
 * 处理表单里的交付物数据。
 * Process deliverable.
 *
 * @param  int    $projectID
 * @param  int    $executionID
 * @param  object $postData
 * @param  string $method
 * @access public
 * @return object
 */
public function processDeliverable($projectID, $executionID, $postData, $method = 'whenCreated')
{
    return $this->loadExtension('zentaomax')->processDeliverable($projectID, $executionID, $postData, $method);
}

/**
 * 上传交付物附件。
 * Upload deliverable file.
 *
 * @param  int    $projectID
 * @param  string $moduleName
 * @param  string $formName
 * @param  string $method
 * @access public
 * @return bool
 */
public function uploadDeliverable($projectID, $moduleName = 'project', $formName = 'deliverable', $method = 'whenCreated')
{
    return $this->loadExtension('zentaomax')->uploadDeliverable($projectID, $moduleName, $formName, $method);
}

/**
 *
 * 将交付物文档复制到项目库中。
 * Move doc to project doc lib.
 *
 * @param  int    $projectID
 * @param  int    $executionID
 * @param  string $method
 * @access public
 * @return bool
 */
public function moveDocToProjectLib($projectID, $executionID = 0, $method = 'whenCreated')
{
    return $this->loadExtension('zentaomax')->moveDocToProjectLib($projectID, $executionID, $method);
}

/**
 * 检查项目是否提交过交付物。
 * Check uploaded deliverable.
 *
 * @param  object $project
 * @access public
 * @return bool
 */
public function checkUploadedDeliverable($project)
{
    return $this->loadExtension('zentaomax')->checkUploadedDeliverable($project);
}

/**
 * 维护交付物页面的保存逻辑。
 * Save deliverable.
 *
 * @param  object $object
 * @param  string $objectType
 * @param  object $postData
 * @access public
 * @return bool
 */
public function saveDeliverable($object, $objectType, $postData)
{
    return $this->loadExtension('zentaomax')->saveDeliverable($object, $objectType, $postData);
}

/**
 * @param object $object
 * @param string $objectType
 */
public function getObjectCode($object, $objectType)
{
    return $this->loadExtension('zentaomax')->getObjectCode($object, $objectType);
}

/**
 * 计算交付物数量。
 * Count deliverable.
 *
 * @param  object $object
 * @param  string $objectType
 * @access public
 * @return string
 */
public function countDeliverable($object, $objectType = 'project')
{
    return $this->loadExtension('zentaomax')->countDeliverable($object, $objectType);
}

/**
 * 获取项目模板列表。
 * Get template list.
 *
 * @param  string $status
 * @param  string $orderBy
 * @param  object $pager
 * @access public
 * @return array
 */
public function getTemplateList($status = 'all', $orderBy = 'id_asc', $pager = null)
{
    return $this->loadExtension('zentaomax')->getTemplateList($status, $orderBy, $pager);
}

/**
 * 更新项目模板。
 * Update project template.
 *
 * @param  int    $projectID
 * @param  object $project
 * @param  object $oldProject
 * @access public
 * @return bool|array
 */
public function updateTemplate($projectID, $project, $oldProject)
{
    return $this->loadExtension('zentaomax')->updateTemplate($projectID, $project, $oldProject);
}

/**
 * 更新项目。
 * Update project.
 *
 * @param  object      $project
 * @param  object      $oldProject
 * @param  object      $postProductData
 * @access public
 * @return array|false
 */
public function update($project, $oldProject, $postProductData = null)
{
    return $this->loadExtension('zentaomax')->update($project, $oldProject, $postProductData);
}

/**
 * 保存复制的执行。
 * Save copy executions.
 *
 * @param  array  $insertExecutions
 * @param  int    $productID
 * @param  int    $copyProjectID
 * @param  object $project
 * @param  int    $projectID
 * @param  string $model
 * @param  string $copyFrom
 * @access public
 * @return array
 */
public function saveExecutions($insertExecutions = array(), $productID = 0, $copyProjectID = 0, $project = null, $projectID = 0, $model = 'scrum', $copyFrom = '')
{
    return $this->loadExtension('zentaomax')->saveExecutions($insertExecutions, $productID, $copyProjectID, $project, $projectID, $model, $copyFrom);
}
