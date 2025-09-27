<?php
class reportTestcase extends testcaseModel
{
    /**
     * 获取用例的基本统计数据。
     * Get basic metrics of case.
     *
     * @param  array  $cases
     * @param  object $data  项目|执行
     * @access public
     * @return object
     */
    public function getBasicMetrics($cases, $data)
    {
        /* 获取有用例的需求数据 */
        /* Get requirements with use cases. */
        $moduleList = $this->loadModel('tree')->getAllModulePairs('testcase');
        $products   = $this->loadModel('product')->getProducts($data->id);
        $storyNum   = $this->dao->select('COUNT(1) AS count')->from(TABLE_PROJECTSTORY)->alias('t1')
            ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story = t2.id')
            ->leftJoin(TABLE_PRODUCT)->alias('t3')->on('t2.product = t3.id')
            ->where('t1.project')->eq($data->id)
            ->andWhere('t2.type')->eq('story')
            ->andWhere('t2.isParent')->eq(0)
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('t3.deleted')->eq(0)
            ->fetch('count');

        $hasCaseStories = array();
        $storyTracker   = array();
        foreach($cases as $item)
        {
            if(isset($products[$item->product])) $item->product = $products[$item->product]->name;

            $item->secondModule = '';
            if($item->module)
            {
                $moduleName = zget($moduleList, $item->module, '/');
                $modules    = explode('/', trim($moduleName, '/'));
                $item->module = $modules[0];
                if(isset($modules[1])) $item->secondModule = $modules[1];
            }

            if($item->story != 0 && !in_array($item->story, $storyTracker))
            {
                $hasCaseStories[] = $item;
                $storyTracker[]   = $item->story;
            }
        }

        /* 根据度量项获取数据 */
        /* Get data based on measurement items. */
        $caseMetrics = array(
            (object)array('scope' => 'user',      'purpose' => 'scale', 'code' => 'count_of_created_case_in_user'),
            (object)array('scope' => 'execution', 'purpose' => 'scale', 'code' => 'count_of_case_in_status_in_execution'),
            (object)array('scope' => 'execution', 'purpose' => 'scale', 'code' => 'count_of_case_in_pri_in_execution'),
            (object)array('scope' => 'execution', 'purpose' => 'scale', 'code' => 'count_of_case_in_result_in_execution'),
            (object)array('scope' => 'execution', 'purpose' => 'scale', 'code' => 'count_of_case_in_type_in_execution'),
            (object)array('scope' => 'product',   'purpose' => 'scale', 'code' => 'count_of_story_in_product_and_module'),
        );
        $caseResults = $this->loadModel('metric')->getResultByCodeFromData($caseMetrics, $cases);

        /* 获取执行下的需求数、用例数、有用例的需求数、需求用例覆盖率、需求用例密度 */
        /* Get the number of requirements, use cases, requirements with use cases, requirement use case coverage, and requirement use case density under execution. */
        $statistics = new stdclass();
        $statistics->storyNum        = $storyNum;
        $statistics->caseNum         = count($cases);
        $statistics->hasCaseStoryNum = count($hasCaseStories);
        $statistics->caseCoverage    = (empty($statistics->storyNum) || empty($statistics->hasCaseStoryNum)) ? 0 : round(($statistics->hasCaseStoryNum / $statistics->storyNum) * 100, 2);
        $statistics->caseCoverage    = $statistics->caseCoverage . '%';
        $statistics->caseDensity     = (empty($statistics->storyNum) || empty($statistics->caseNum)) ? 0 : round($statistics->caseNum / $statistics->storyNum, 2);
        $statistics->productMap      = $caseResults['count_of_story_in_product_and_module'];

        /* 获取按团队成员统计的创建用例数和执行用例次数 */
        /* Get statistics on the number of created and executed use cases by team members. */
        $tab               = $this->app->tab;
        $members           = $this->loadModel($tab)->getTeamMembers($data->id);
        $userCreatedCases  = isset($caseResults['count_of_created_case_in_user']) ? $caseResults['count_of_created_case_in_user'] : array();
        $userExecutedCases = array();
        $executedCases     = $this->getExecutedCaseActions($cases, $data);
        $actorCount        = array();
        $userPairs         = array();
        foreach($executedCases as $item)
        {
            $actor = $item->actor;
            if(!isset($actorCount[$actor])) $actorCount[$actor] = 0;
            $actorCount[$actor]++;
        }
        foreach($actorCount as $user => $value) $userExecutedCases[] = array('user' => $user, 'value' => $value);
        foreach($members as $member)            $userPairs[$member->account] = $member->realname;
        $statistics->userCreatedCases  = $this->loadModel('execution')->processUserStats($userPairs, $userCreatedCases);
        $statistics->userExecutedCases = $this->execution->processUserStats($userPairs, $userExecutedCases);

        /* 用例状态分布、用例优先级分布、用例结果分布、用例类型分布 */
        /* Use case state distribution, use case priority distribution, use case result distribution, use case type distribution */
        $statusMetrics = isset($caseResults['count_of_case_in_status_in_execution']) ? $caseResults['count_of_case_in_status_in_execution'] : array();
        $priMetrics    = isset($caseResults['count_of_case_in_pri_in_execution']) ? $caseResults['count_of_case_in_pri_in_execution'] : array();
        $resultMetrics = isset($caseResults['count_of_case_in_result_in_execution']) ? $caseResults['count_of_case_in_result_in_execution'] : array();
        $typeMetrics   = isset($caseResults['count_of_case_in_type_in_execution']) ? $caseResults['count_of_case_in_type_in_execution'] : array();
        unset($this->lang->testcase->resultList['n/a']);
        foreach(array('status', 'pri', 'result', 'type') as $field)
        {
            $fieldName  = $field . 'List';
            $mapName    = $field . 'Map';
            $resultName = array();
            $chars      = array('"', "'", '\\');
            foreach($this->lang->testcase->$fieldName as $code => $name)
            {
                $name = str_replace($chars, '', $name);
                $resultName[$code] = array('count' => 0, 'name' => $name);
                foreach(${$field . 'Metrics'} as $metric)
                {
                    if($metric[$field] == $code)   $resultName[$code]['count'] = $metric['value'];
                    if(!isset($resultName[$code])) $resultName[$code] = array('name' => $metric[$field], 'count' => $metric['value']);
                }
            }

            $statistics->$mapName = $resultName;
        }

        return $statistics;
    }

    /**
     * 获取执行用例的记录。
     * Get the executed case actions.
     *
     * @param  array  $cases
     * @param  object $data  项目|执行
     * @access public
     * @return array
     */
    public function getExecutedCaseActions($cases, $data)
    {
        if(empty($cases)) return array();

        $caseIdList = array_keys($cases);
        return $this->dao->select('actor')->from(TABLE_ACTION)
            ->where('objectType')->eq('case')
            ->andWhere('objectID')->in($caseIdList)
            ->andWhere('action')->eq('run')
            ->fetchAll();
    }

    /**
     * 构建用例基本统计配置。
     * Build basic config of case.
     *
     * @param  array  $cases
     * @param  object $data  项目|执行
     * @access public
     * @return array
     */
    public function buildBasicConfig($cases, $data)
    {
        $this->loadModel('report');
        $settings = array();
        $metrics  = $this->getBasicMetrics($cases, $data);
        $xAxis    = $this->config->report->reportChart->xAxis;
        $yAxis    = 80;

        $firstContents = array(
            (object)array('title' => $metrics->storyNum,        'desc' => $this->lang->testcase->report->storyNum),
            (object)array('title' => $metrics->caseNum,         'desc' => $this->lang->testcase->report->caseNum),
            (object)array('title' => $metrics->hasCaseStoryNum, 'desc' => $this->lang->testcase->report->hasCaseStoryNum),
            (object)array('title' => $metrics->caseCoverage,    'desc' => $this->lang->testcase->report->caseCoverage, 'help' => $this->lang->testcase->report->tips->caseCoverage),
            (object)array('title' => $metrics->caseDensity,     'desc' => $this->lang->testcase->report->caseDensity,  'help' => $this->lang->testcase->report->tips->caseDensity),
        );
        $settings = array_merge($settings, $this->report->buildTextGroupChartConfig($firstContents, $xAxis, $yAxis));
        $yAxis   += $this->config->report->reportChart->textHeight + $this->config->report->reportChart->padding * 7.2;
        foreach(array('userCreatedCases', 'userExecutedCases') as $index => $field) $settings = array_merge($settings, $this->report->buildBarChartConfig($this->lang->testcase->report->$field, $metrics->$field, $yAxis, 'cluBarY', $index, 2));

        $yAxis   += $this->config->report->reportChart->barHeight + $this->config->report->reportChart->padding * 6;
        $settings = array_merge($settings, $this->report->buildSunburstChartConfig($this->lang->testcase->report->productMap, $metrics->productMap, $xAxis, $yAxis, $this->lang->testcase->report->tips->productMap));

        $yAxis += $this->config->report->reportChart->oneHalf + $this->config->report->reportChart->padding * 4.5;
        foreach(array('statusMap', 'priMap', 'resultMap', 'typeMap') as $index => $field) $settings = array_merge($settings, $this->report->buildPieChartConfig($this->lang->testcase->report->$field, $metrics->$field, $xAxis, $yAxis, $index, 2, $this->lang->testcase->report->caseNum));

        return $settings;
    }
}
