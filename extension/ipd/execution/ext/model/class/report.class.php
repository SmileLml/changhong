<?php
class reportExecution extends executionModel
{
    /**
     * 获取基本统计数据。
     * Get basic metrics.
     *
     * @param  array  $bugs
     * @param  object $data 项目|执行
     * @param  string $type
     * @access public
     * @return object
     */
    public function getBugBasicMetrics($bugs, $data, $type = 'execution')
    {
        $stories    = $this->loadModel('story')->getExecutionStories($data->id);
        $bugMetrics = array(
            (object)array('scope' => 'execution', 'purpose' => 'scale', 'code' => 'count_of_effective_bug_in_execution'),
            (object)array('scope' => 'execution', 'purpose' => 'scale', 'code' => 'count_of_case_bug_in_execution'),
            (object)array('scope' => 'execution', 'purpose' => 'scale', 'code' => 'count_of_fixed_bug_in_execution'),
            (object)array('scope' => 'execution', 'purpose' => 'scale', 'code' => 'count_of_bug_in_pri_in_execution'),
            (object)array('scope' => 'execution', 'purpose' => 'scale', 'code' => 'count_of_bug_in_resolution_in_execution'),
            (object)array('scope' => 'execution', 'purpose' => 'scale', 'code' => 'count_of_bug_in_severity_in_execution'),
            (object)array('scope' => 'execution', 'purpose' => 'scale', 'code' => 'count_of_bug_in_type_in_execution'),
            (object)array('scope' => 'execution', 'purpose' => 'scale', 'code' => 'count_of_bug_in_status_execution'),
            (object)array('scope' => 'product',   'purpose' => 'scale', 'code' => 'count_of_story_in_product_and_module'),
        );
        $storyMetrics = array(
            (object)array('code' => 'count_of_developed_story_in_execution', 'scope' => 'execution', 'purpose' => 'scale')
        );
        $bugResults   = $this->loadModel('metric')->getResultByCodeFromData($bugMetrics, $bugs);
        $storyResults = $this->metric->getResultByCodeFromData($storyMetrics, $stories);

        $statistics = new stdclass();
        $statistics->total          = count($bugs);
        $statistics->effective      = isset($bugResults['count_of_effective_bug_in_execution'][0]) ? array_sum(array_column($bugResults['count_of_effective_bug_in_execution'], 'value')) : 0;
        $statistics->useCase        = isset($bugResults['count_of_case_bug_in_execution'][0]) ? array_sum(array_column($bugResults['count_of_case_bug_in_execution'], 'value')) : 0;
        $statistics->fixed          = isset($bugResults['count_of_fixed_bug_in_execution'][0]) ? array_sum(array_column($bugResults['count_of_fixed_bug_in_execution'], 'value')) : 0;
        $statistics->developedStory = isset($storyResults['count_of_developed_story_in_execution'][0]) ? array_sum(array_column($storyResults['count_of_developed_story_in_execution'], 'value')) : 0;
        $statistics->defect         = empty($statistics->effective) || empty($statistics->developedStory) ? 0 : round($statistics->effective / $statistics->developedStory, 2);
        $statistics->efficient      = (empty($statistics->effective) || empty($statistics->total)) ? 0 : round($statistics->effective / $statistics->total, 4);
        $statistics->fixedRate      = (empty($statistics->fixed) || empty($statistics->effective)) ? 0 : round($statistics->fixed / $statistics->effective, 4);
        $statistics->caseBugRate    = (empty($statistics->useCase) || empty($statistics->total)) ? 0 : round($statistics->useCase / $statistics->total, 4);
        $statistics->productMap     = $bugResults['count_of_story_in_product_and_module'];

        $severityMetrics   = isset($bugResults['count_of_bug_in_severity_in_execution']) ? $bugResults['count_of_bug_in_severity_in_execution'] : array();
        $priMetrics        = isset($bugResults['count_of_bug_in_pri_in_execution']) ? $bugResults['count_of_bug_in_pri_in_execution'] : array();
        $resolutionMetrics = isset($bugResults['count_of_bug_in_resolution_in_execution']) ? $bugResults['count_of_bug_in_resolution_in_execution'] : array();
        $typeMetrics       = isset($bugResults['count_of_bug_in_type_in_execution']) ? $bugResults['count_of_bug_in_type_in_execution'] : array();
        $statusMetrics     = isset($bugResults['count_of_bug_in_status_execution']) ? $bugResults['count_of_bug_in_status_execution'] : array();
        unset($this->lang->bug->resolutionList['']);
        foreach(array('severity', 'pri', 'resolution', 'type') as $field)
        {
            $fieldName  = $field . 'List';
            $mapName    = $field . 'Map';
            $resultName = array();
            $chars      = array('"', "'", '\\');
            foreach($this->lang->bug->$fieldName as $code => $name)
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
        /* 修改状态为空的位置, 获取不同状态的数量。*/
        /* Modify the position with an empty status, obtain the number of different states.*/
        $statusList = $this->lang->bug->statusList;
        unset($statusList['']);
        $statusList[''] = '';
        $statistics->statusDistribution = array();
        foreach($statusList as $status => $statusName) $statistics->statusDistribution[$status] = array('count' => 0, 'name' => $statusName ? $statusName : $this->lang->null);
        foreach($statusMetrics as $item)
        {
            $status = $item['status'];
            if(isset($statistics->statusDistribution[$status])) $statistics->statusDistribution[$status]['count'] = $item['value'];
        }
        if(empty($statistics->statusDistribution['']['count'])) unset($statistics->statusDistribution['']);

        return $statistics;
    }

    /**
     * 构建Bug基本统计配置。
     * Build chart config.
     *
     * @param  array  $bugs
     * @param  object $data 项目|执行
     * @param  string $type
     * @access public
     * @return array
     */
    public function buildBasicBugConfig($bugs, $data, $type = 'execution')
    {
        $moduleList = $this->loadModel('tree')->getAllModulePairs('bug');
        $products   = $this->loadModel('product')->getProducts($data->id);

        foreach($bugs as $item)
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
        }

        $this->loadModel('report');
        $settings = array();
        $metrics  = $this->getBugBasicMetrics($bugs, $data, $type);
        $xAxis    = $this->config->report->reportChart->xAxis;
        $yAxis    = 80;

        $firstContents = array(
            (object)array('title' => $metrics->total,     'desc' => $this->lang->execution->report->bug->total),
            (object)array('title' => $metrics->effective, 'desc' => $this->lang->execution->report->bug->effective, 'help' => $this->lang->execution->report->tips->effective),
            (object)array('title' => $metrics->useCase,   'desc' => $this->lang->execution->report->bug->useCase),
        );
        $settings = array_merge($settings, $this->report->buildTextGroupChartConfig($firstContents, $xAxis, $yAxis));
        $xAxis   -= $this->config->report->reportChart->fullWidth / 2 - 940;
        $yAxis   += $this->config->report->reportChart->textHeight + $this->config->report->reportChart->padding * 6.2;

        $secondContents = array(
            (object)array('title' => $metrics->fixed,          'desc' => $this->lang->execution->report->bug->fixed, 'help' => $this->lang->execution->report->tips->fixed),
            (object)array('title' => $metrics->developedStory, 'desc' => $this->lang->execution->report->bug->developedStory),
            (object)array('title' => $metrics->defect,         'desc' => $this->lang->execution->report->bug->defect, 'help' => $this->lang->execution->report->tips->defect),
        );
        $settings = array_merge($settings, $this->report->buildTextGroupChartConfig($secondContents, $xAxis, $yAxis));
        $yAxis   += $this->config->report->reportChart->textHeight + $this->config->report->reportChart->padding * 10;
        $xAxis   -= $this->config->report->reportChart->fullWidth / 2 - 920;
        foreach(array('efficient', 'fixedRate', 'caseBugRate') as $index => $field) $settings = array_merge($settings, $this->report->buildWaterChartConfig($this->lang->execution->report->bug->$field, $metrics->$field,  $xAxis, $yAxis, $index, $this->lang->execution->report->tips->$field, 3));

        $yAxis   += $this->config->report->reportChart->waterHeight + $this->config->report->reportChart->padding * 8.8;
        $xAxis   -= $this->config->report->reportChart->fullWidth / 2 + 293;
        $settings = array_merge($settings, $this->report->buildBarChartConfig($this->lang->execution->report->bug->statusDistribution, $metrics->statusDistribution, $yAxis));

        $yAxis   += $this->config->report->reportChart->barHeight + $this->config->report->reportChart->padding * 6.3;
        $settings = array_merge($settings, $this->report->buildSunburstChartConfig($this->lang->execution->report->bug->productMap, $metrics->productMap, $xAxis, $yAxis, $this->lang->execution->report->tips->productMap));

        $yAxis += $this->config->report->reportChart->oneHalf + $this->config->report->reportChart->padding * 4.5;
        foreach(array('severityMap', 'priMap', 'resolutionMap', 'typeMap') as $index => $field) $settings = array_merge($settings, $this->report->buildPieChartConfig($this->lang->execution->report->bug->$field, $metrics->$field, $xAxis, $yAxis, $index, 2, $this->lang->execution->report->bug->count));
        return $settings;
    }

    /**
     * 获取进度分析数据。
     * Get progress metrics.
     *
     * @param  array  $bugs
     * @param  object $data 项目|执行
     * @param  string $type
     * @access public
     * @return object
     */
    public function getBugProgressMetrics($bugs, $data, $type = 'execution')
    {
        $bugMetrics = array(
            (object)array('code' => 'count_of_activated_bug_in_execution',      'scope' => 'execution', 'purpose' => 'scale'),
            (object)array('code' => 'count_of_fixed_bug_in_execution',          'scope' => 'execution', 'purpose' => 'scale'),
            (object)array('code' => 'count_of_daily_created_bug_in_execution',  'scope' => 'execution', 'purpose' => 'scale'),
            (object)array('code' => 'count_of_daily_resolved_bug_in_execution', 'scope' => 'execution', 'purpose' => 'scale'),
            (object)array('code' => 'count_of_daily_closed_bug_in_execution',   'scope' => 'execution', 'purpose' => 'scale'),
            (object)array('code' => 'count_of_created_bug_in_user',             'scope' => 'user',      'purpose' => 'scale'),
            (object)array('code' => 'count_of_resolved_bug_in_user',            'scope' => 'user',      'purpose' => 'scale'),
        );
        $bugResults = $this->loadModel('metric')->getResultByCodeFromData($bugMetrics, $bugs);

        $statistics = new stdclass();
        $statistics->total  = count($bugs);
        $statistics->active = isset($bugResults['count_of_activated_bug_in_execution'][0]) ? array_sum(array_column($bugResults['count_of_activated_bug_in_execution'], 'value')) : 0;
        $statistics->fixed  = isset($bugResults['count_of_fixed_bug_in_execution'][0]) ? array_sum(array_column($bugResults['count_of_fixed_bug_in_execution'], 'value')) : 0;

        $dailyData = array();
        $dailyData['created']  = isset($bugResults['count_of_daily_created_bug_in_execution']) ? $bugResults['count_of_daily_created_bug_in_execution'] : array();
        $dailyData['resolved'] = isset($bugResults['count_of_daily_resolved_bug_in_execution']) ? $bugResults['count_of_daily_resolved_bug_in_execution'] : array();
        $dailyData['closed']   = isset($bugResults['count_of_daily_closed_bug_in_execution']) ? $bugResults['count_of_daily_closed_bug_in_execution'] : array();

        $endData = $data->end ? $data->end : date('Y-m-d');
        $dates   = $this->processDateData($data->begin, $endData);
        $statistics->dailyNum = $this->processDailyBug($dailyData, $dates);

        /* 按团队成员统计的创建Bug数和解决Bug数 */
        /* Number of Bugs Created and Resolved by Team Members. */
        $members          = $this->loadModel($type)->getTeamMembers($data->id);
        $userCreatedBugs  = isset($bugResults['count_of_created_bug_in_user']) ? $bugResults['count_of_created_bug_in_user'] : array();
        $userResolvedBugs = isset($bugResults['count_of_resolved_bug_in_user']) ? $bugResults['count_of_resolved_bug_in_user'] : array();
        $userPairs        = array();
        foreach($members as $member) $userPairs[$member->account] = $member->realname;
        $statistics->userCreatedBugs  = $this->processUserStats($userPairs, $userCreatedBugs);
        $statistics->userResolvedBugs = $this->processUserStats($userPairs, $userResolvedBugs);

        return $statistics;
    }

    /**
     * 处理用户统计数据。
     * Process user stats.
     *
     * @param  array  $userPairs
     * @param  array  $userData
     * @access public
     * @return array
     */
    public function processUserStats($userPairs, $userData)
    {
        $data = array();
        foreach ($userPairs as $username => $name)
        {
            $value = 0;
            foreach($userData as $item)
            {
                if($item['user'] === $username)
                {
                    $value = $item['value'];
                    break;
                }
            }
            $data[] = ['user' => $username, 'value' => $value, 'name' => $name];
        }
        return $data;
    }

    /**
     * 处理每日Bug数据。
     * Process daily bug data.
     *
     * @param  array  $data
     * @param  array  $xAxisData
     * @access public
     * @return array
     */
    public function processDailyBug($data, $xAxisData)
    {
        $dateMap = array();
        foreach($data as $key => $dailyItem)
        {
            foreach($dailyItem as $item)
            {
                if(is_string($item)) continue;

                $dateKey = $item['year'] . '-' . $item['month'] . '-' . $item['day'];
                if(isset($dateMap[$key][$dateKey]))
                {
                    $dateMap[$key][$dateKey] += $item['value'];
                }
                else
                {
                    $dateMap[$key][$dateKey] = $item['value'];
                }
            }
        }
        $config   = array(array_merge(array('name'), $this->lang->execution->report->bug->dailyTitles));
        $showData = array();
        foreach($xAxisData as $date)
        {
            $row = array_merge(array($date), array(0, 0, 0));
            if(isset($dateMap['created'][$date]))  $row[1] = $dateMap['created'][$date];
            if(isset($dateMap['resolved'][$date])) $row[2] = $dateMap['resolved'][$date];
            if(isset($dateMap['closed'][$date]))   $row[3] = $dateMap['closed'][$date];
            $showData[] = $row;
        }
        $config[] = $showData;

        return $config;
    }

    /**
     * 处理日期数据。
     * Process date data.
     *
     * @param  string $beginDate
     * @param  string $endDate
     * @access public
     * @return array
     */
    public function processDateData($beginDate, $endDate)
    {
        $begin = new DateTime($beginDate);
        $end   = new DateTime($endDate);
        $dates = array();
        while($begin <= $end)
        {
            $dates[] = $begin->format('Y-m-d');
            $begin->modify('+1 day');
        }
        return $dates;
    }

    /**
     * 构建进度分析配置。
     * Build chart config.
     *
     * @param  array  $bugs
     * @param  object $data 项目|执行
     * @param  string $type
     * @access public
     * @return array
     */
    public function buildProgressBugConfig($bugs, $data, $type = 'execution')
    {
        $this->loadModel('report');
        $settings = array();
        $metrics  = $this->getBugProgressMetrics($bugs, $data, $type);
        $xAxis    = $this->config->report->reportChart->xAxis;
        $yAxis    = 100;
        $width    = $this->config->report->reportChart->oneThird + $this->config->report->reportChart->padding / 2 + 8;
        foreach(array('total', 'active', 'fixed') as $index => $field)
        {
            $tips     = isset($this->lang->execution->report->tips->$field) ? $this->lang->execution->report->tips->$field : '';
            $settings = array_merge($settings, $this->report->buildTextChartConfig($metrics->$field, $this->lang->execution->report->bug->$field, $xAxis, $yAxis, $index, $width, 2, $tips));
        }

        $yAxis   += $this->config->report->reportChart->textHeight + $this->config->report->reportChart->padding * 5;
        $xAxis   -= $this->config->report->reportChart->fullWidth / 2;
        $settings = array_merge($settings, $this->report->buildBarChartConfig($this->lang->execution->report->bug->dailyNum, $metrics->dailyNum, $yAxis, 'line'));

        $yAxis += $this->config->report->reportChart->barHeight + $this->config->report->reportChart->padding * 5.8;
        foreach(array('userCreated', 'userResolved') as $index => $field) $settings = array_merge($settings, $this->report->buildBarChartConfig($this->lang->execution->report->bug->{$field . 'Bugs'}, $metrics->{$field . 'Bugs'}, $yAxis, 'cluBarY', $index, 2));
        return $settings;
    }
}
