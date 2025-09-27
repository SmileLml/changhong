<?php
class reportTask extends taskModel
{
    /**
     * 获取基本统计数据。
     * Get basic metrics.
     *
     * @param  array  $taskList
     * @access public
     * @return object
     */
    public function getBasicMetrics($taskList)
    {
        $users      = $this->loadModel('user')->getPairs('noletter');
        $moduleList = $this->loadModel('tree')->getAllModulePairs('task');
        foreach($taskList as $task)
        {
            $task->finishedBy    = zget($users, $task->finishedBy);
            $task->projectStatus = $task->executionStatus = $task->teamStatus = 'doing';
            $task->assignedTo    = zget($users, $task->assignedTo);
            $task->account       = $task->assignedTo;
            if(!$task->account && !empty($task->team[0]->account)) $task->account = zget($users, $task->team[0]->account);
            if($task->module)
            {
                $moduleName = zget($moduleList, $task->module, '/');
                if($moduleName != '/') $moduleName = explode('/', trim($moduleName, '/'))[0];
                $task->module = $moduleName;
            }
        }

        $metrics = array(
            (object)array('code' => 'count_of_task',                  'scope' => 'system',    'purpose' => 'scale'),
            (object)array('code' => 'count_of_finished_task',         'scope' => 'system',    'purpose' => 'scale'),
            (object)array('code' => 'consume_of_task_in_execution',   'scope' => 'execution', 'purpose' => 'hour'),
            (object)array('code' => 'left_of_task_in_execution',      'scope' => 'execution', 'purpose' => 'hour'),
            (object)array('code' => 'count_of_assigned_task',         'scope' => 'task',      'purpose' => 'scale'),
            (object)array('code' => 'count_of_finished_task_in_user', 'scope' => 'user',      'purpose' => 'scale'),
            (object)array('code' => 'count_of_task_in_status',        'scope' => 'task',      'purpose' => 'scale'),
            (object)array('code' => 'count_of_task_in_type',          'scope' => 'task',      'purpose' => 'scale'),
            (object)array('code' => 'count_of_task_in_module',        'scope' => 'task',      'purpose' => 'scale'),
            (object)array('code' => 'count_of_task_in_pri',           'scope' => 'task',      'purpose' => 'scale'),
            (object)array('code' => 'count_of_task_in_reason',        'scope' => 'task',      'purpose' => 'scale'),
            (object)array('code' => 'rate_of_finished_task',          'scope' => 'task',      'purpose' => 'rate'),
            (object)array('code' => 'rate_of_hour_process_task',      'scope' => 'task',      'purpose' => 'rate'),
        );

        $results = $this->loadModel('metric')->getResultByCodeFromData($metrics, $taskList);

        $statistics = new stdclass();
        $statistics->taskNum   = isset($results['count_of_task'][0])                ? $results['count_of_task'][0]['value']                : 0;
        $statistics->doneNum   = isset($results['count_of_finished_task'][0])       ? $results['count_of_finished_task'][0]['value']       : 0;
        $statistics->consumed  = isset($results['consume_of_task_in_execution'][0]) ? $results['consume_of_task_in_execution'][0]['value'] : 0;
        $statistics->left      = isset($results['left_of_task_in_execution'][0])    ? $results['left_of_task_in_execution'][0]['value']    : 0;
        $statistics->assignMap = isset($results['count_of_assigned_task'])  ? $results['count_of_assigned_task']           : array();
        $statistics->ownerMap  = isset($results['count_of_finished_task_in_user'])  ? $results['count_of_finished_task_in_user']           : array();
        $statistics->moduleMap = isset($results['count_of_task_in_module'])         ? $results['count_of_task_in_module']                  : array();
        $statistics->doneRate  = isset($results['rate_of_finished_task'][0])        ? $results['rate_of_finished_task'][0]['value']        : 0;
        $statistics->taskRate  = isset($results['rate_of_hour_process_task'][0])    ? $results['rate_of_hour_process_task'][0]['value']    : 0;

        if($this->app->tab == 'project')
        {
            $statistics->consumed = isset($results['consume_of_task_in_execution'][0]) ? array_sum(array_column($results['consume_of_task_in_execution'], 'value')) : 0;
            $statistics->left     = isset($results['left_of_task_in_execution'][0]) ? array_sum(array_column($results['left_of_task_in_execution'], 'value')) : 0;
        }

        $statusMetrics = isset($results['count_of_task_in_status']) ? $results['count_of_task_in_status'] : array();
        $typeMetrics   = isset($results['count_of_task_in_type'])   ? $results['count_of_task_in_type']   : array();
        $priMetrics    = isset($results['count_of_task_in_pri'])    ? $results['count_of_task_in_pri']    : array();
        $reasonMetrics = isset($results['count_of_task_in_reason']) ? $results['count_of_task_in_reason'] : array();

        $chars   = array('"', "'", '\\');
        $mapList = array();
        foreach(array('status', 'type', 'pri', 'reason') as $field)
        {
            $fieldName = $field . 'List';
            $mapName   = $field . 'Map';
            $mapList[$mapName] = array();
            foreach($this->lang->task->$fieldName as $code => $name)
            {
                $name = str_replace($chars, '', $name);
                $mapList[$mapName][$code] = array('count' => 0, 'name' => $name);
                foreach(${$field . 'Metrics'} as $metric)
                {
                    if($metric[$field] == $code) $mapList[$mapName][$code]['count'] = $metric['value'];

                    if(!isset($mapList[$mapName][$code])) $mapList[$mapName][$code] = array('name' => $metric[$field], 'count' => $metric['value']);
                }
            }

            $statistics->$mapName = $mapList[$mapName];
        }
        return $statistics;
    }

    /**
     * 构建图表配置。
     * Build chart config.
     *
     * @param  array  $taskList
     * @access public
     * @return array
     */
    public function buildBasicChartConfig($taskList)
    {
        $this->loadModel('report');
        $metrics = $this->getBasicMetrics($taskList);

        $settings = array();
        $xAxis    = $this->config->report->reportChart->xAxis;
        $yAxis    = 100;
        foreach(array('taskNum', 'doneNum', 'consumed', 'left') as $index => $field) $settings = array_merge($settings, $this->report->buildTextChartConfig($metrics->$field, $this->lang->task->report->$field, $xAxis, $yAxis, $index));

        $xAxis  = 0;
        $yAxis += $this->config->report->reportChart->textHeight + $this->config->report->reportChart->padding * 8;
        foreach(array('doneRate', 'taskRate') as $index => $field) $settings = array_merge($settings, $this->report->buildWaterChartConfig($this->lang->task->report->$field, $metrics->$field, $xAxis, $yAxis, $index, $this->lang->task->report->tips->$field));

        $yAxis   += $this->config->report->reportChart->waterHeight + $this->config->report->reportChart->padding * 9;
        $settings = array_merge($settings, $this->report->buildBarChartConfig($this->lang->task->report->statusDistribution, $metrics->statusMap, $yAxis));

        $xAxis  = $this->config->report->reportChart->xAxis;
        $yAxis += $this->config->report->reportChart->barHeight + $this->config->report->reportChart->padding * 6;
        foreach(array('assign', 'owner', 'module', 'type', 'pri', 'reason') as $index => $field) $settings = array_merge($settings, $this->report->buildPieChartConfig($this->lang->task->report->{$field . 'Distribution'}, $metrics->{$field . 'Map'}, $xAxis, $yAxis, $index));
        return $settings;
    }

    /**
     * 获取精度分析数据。
     * Get precision analysis data.
     *
     * @param  array  $taskList
     * @param  object $data     项目|执行
     * @param  string $type
     * @access public
     * @return object
     */
    public function getProgressMetrics($taskList, $data, $type = 'execution')
    {
        $this->lang->task->statusList[''] = $this->lang->null;
        $this->lang->task->typeList['']   = $this->lang->null;

        if($type == 'execution')
        {
            $startDate = helper::isZeroDate($data->realBegan) ? $data->begin : $data->realBegan;
            $endDate   = helper::isZeroDate($data->realEnd) ? date('Y-m-d') : $data->realEnd;
        }
        elseif($type == 'project')
        {
            $startDate = date('Y-m-d', strtotime('-13 days'));
            $endDate   = date('Y-m-d');
        }
        foreach($taskList as $task)
        {
            $task->executionStartDate = $startDate;
            $task->executionEndDate   = $endDate;
            $task->finishedDate       = helper::isZeroDate($task->finishedDate) ? '' : substr($task->finishedDate, 0, 10);
        }

        $metrics = array(
            (object)array('code' => 'rate_of_finished_task',               'scope' => 'task', 'purpose' => 'rate'),
            (object)array('code' => 'rate_of_devel_finished_task',         'scope' => 'task', 'purpose' => 'rate'),
            (object)array('code' => 'rate_of_test_finished_task',          'scope' => 'task', 'purpose' => 'rate'),
            (object)array('code' => 'count_of_delayed_finished_task',      'scope' => 'task', 'purpose' => 'scale'),
            (object)array('code' => 'rate_of_hour_process_task_in_type',   'scope' => 'task', 'purpose' => 'rate'),
            (object)array('code' => 'count_of_task_in_type_status',        'scope' => 'task', 'purpose' => 'scale'),
        );
        $results = $this->loadModel('metric')->getResultByCodeFromData($metrics, $taskList);

        $statistics = new stdclass();
        $statistics->doneRate = isset($results['rate_of_finished_task'][0])       ? $results['rate_of_finished_task'][0]['value']       : 0;
        $statistics->devRate  = isset($results['rate_of_devel_finished_task'][0]) ? $results['rate_of_devel_finished_task'][0]['value'] : 0;
        $statistics->testRate = isset($results['rate_of_test_finished_task'][0])  ? $results['rate_of_test_finished_task'][0]['value']  : 0;
        $statistics->dailyNum = isset($results['count_of_delayed_finished_task']) ? $results['count_of_delayed_finished_task']          : array();

        $typeHour   = isset($results['rate_of_hour_process_task_in_type']) ? $results['rate_of_hour_process_task_in_type'] : array();
        $statusData = isset($results['count_of_task_in_type_status'])      ? $results['count_of_task_in_type_status']      : array();

        $chars = array('"', "'", '\\');
        $statistics->typeMap = array();
        foreach($this->lang->task->typeList as $type => $typeName)
        {
            $typeName = str_replace($chars, '', $typeName);
            $consumed = isset($typeHour[$type]['consumed']) ? round($typeHour[$type]['consumed'], 2)         : 0;
            $left     = isset($typeHour[$type]['left'])     ? round($typeHour[$type]['left'], 2)             : 0;
            $rate     = isset($typeHour[$type]['rate'])     ? round($typeHour[$type]['rate'] * 100, 2) . '%' : '0%';
            if(!$type && !$consumed && !$left) continue;

            $statistics->typeMap[] = array($typeName, $consumed, $left, $rate);
        }

        $statistics->statusMap = array();
        foreach($this->lang->task->typeList as $type => $typeName)
        {
            $typeName = str_replace($chars, '', $typeName);
            if(!isset($statusData[$type])) $statusData[$type] = array();

            $row = array($typeName);
            foreach($this->lang->task->statusList as $status => $statusName) $row[] = isset($statusData[$type][$status]) ? $statusData[$type][$status] : 0;
            $statistics->statusMap[] = $row;
        }
        return $statistics;
    }

    /**
     * 构建进度分析图表配置。
     * Build process chart config.
     *
     * @param  array  $taskList
     * @param  object $data     项目|执行
     * @param  string $type
     * @access public
     * @return array
     */
    public function buildProgressChartConfig($taskList, $data, $type = 'execution')
    {
        $this->loadModel('report');
        $metrics  = $this->getProgressMetrics($taskList, $data, $type);
        $xAxis    = 0;
        $yAxis    = 150;
        $settings = array();
        foreach(array('doneRate', 'devRate', 'testRate') as $index => $field) $settings = array_merge($settings, $this->report->buildWaterChartConfig($this->lang->task->report->$field, $metrics->$field, $xAxis, $yAxis, $index, $this->lang->task->report->tips->$field, 3));

        $xAxis   = $this->config->report->reportChart->xAxis;
        $yAxis  += $this->config->report->reportChart->waterHeight + $this->config->report->reportChart->padding * 7;
        $headers = array(array(
            array('field' => 'name',     'label' => $this->lang->task->report->taskType, 'name' => 'name'),
            array('field' => 'consumed', 'label' => $this->lang->task->report->taskCost, 'name' => 'consumed'),
            array('field' => 'left',     'label' => $this->lang->task->report->leftTime, 'name' => 'left'),
            array('field' => 'rate',     'label' => $this->lang->task->report->progress, 'name' => 'rate', 'helpIcon' => 'HelpCircleOutline', 'helpIconSize' => 20, 'helpIconColor' => '#52525B', 'helpPosition' => 'end', 'hint' => $this->lang->task->report->tips->progress),
        ));
        $settings = array_merge($settings, $this->report->buildTableChartConfig($this->lang->task->report->typeMap, $headers, $metrics->typeMap, $yAxis, 4));

        $yAxis  += $this->config->report->reportChart->barHeight + $this->config->report->reportChart->padding * 9;
        $headers = array(array(
            array('field' => 'type',   'label' => $this->lang->task->report->taskType, 'name' => 'type',   'isGroup' => true,  'isSlice' => false, 'rowspan' => 2),
            array('field' => 'status', 'label' => $this->lang->task->report->taskNum,  'name' => 'status', 'isGroup' => false, 'isSlice' => true,  'colspan' => count($this->lang->task->statusList)),
        ));

        foreach($this->lang->task->statusList as $status => $statusName) $headers[1][] = array('field' => $status, 'label' => $statusName, 'name' => $status, 'isGroup' => true, 'isSlice' => false, 'rowspan' => 1);
        $settings = array_merge($settings, $this->report->buildTableChartConfig($this->lang->task->report->statusMap, $headers, $metrics->statusMap, $yAxis, count($this->lang->task->statusList) + 1));

        $yAxis     += $this->config->report->reportChart->barHeight + $this->config->report->reportChart->padding * 10 + 10;
        $dailyTitle = $this->app->tab == 'project' ? $this->lang->task->report->projectDailyNum : $this->lang->task->report->dailyNum;
        $settings   = array_merge($settings, $this->report->buildBarChartConfig($dailyTitle, $metrics->dailyNum, $yAxis));
        return $settings;
    }

    /**
     * 获取资源分析数据。
     * Get resource analysis data.
     *
     * @param  array  $taskList
     * @param  object $data     项目|执行
     * @param  string $type
     * @access public
     * @return object
     */
    public function getResourceMetrics($taskList, $data, $type = 'execution')
    {
        $metrics = array(
            (object)array('code' => 'count_of_task',                             'scope' => 'system',    'purpose' => 'scale'),
            (object)array('code' => 'consume_of_task_in_execution',              'scope' => 'execution', 'purpose' => 'hour'),
            (object)array('code' => 'count_of_frombug_task_in_execution',        'scope' => 'execution', 'purpose' => 'scale'),
            (object)array('code' => 'consume_of_frombug_task_in_execution',      'scope' => 'execution', 'purpose' => 'hour'),
            (object)array('code' => 'consume_rate_of_frombug_task_in_execution', 'scope' => 'execution', 'purpose' => 'rate'),
            (object)array('code' => 'rate_of_frombug_task_in_execution',         'scope' => 'execution', 'purpose' => 'rate'),
        );

        $results = $this->loadModel('metric')->getResultByCodeFromData($metrics, $taskList);

        $statistics = new stdclass();
        $statistics->taskNum        = isset($results['count_of_task'][0])                             ? $results['count_of_task'][0]['value']                             : 0;
        $statistics->consumed       = isset($results['consume_of_task_in_execution'][0])              ? $results['consume_of_task_in_execution'][0]['value']              : 0;
        $statistics->bugTaskNum     = isset($results['count_of_frombug_task_in_execution'][0])        ? $results['count_of_frombug_task_in_execution'][0]['value']        : 0;
        $statistics->bugConsume     = isset($results['consume_of_frombug_task_in_execution'][0])      ? $results['consume_of_frombug_task_in_execution'][0]['value']      : 0;
        $statistics->bugRate        = isset($results['rate_of_frombug_task_in_execution'][0])         ? $results['rate_of_frombug_task_in_execution'][0]['value']         : 0;
        $statistics->bugConsumeRate = isset($results['consume_rate_of_frombug_task_in_execution'][0]) ? $results['consume_rate_of_frombug_task_in_execution'][0]['value'] : 0;

        if($this->app->tab == 'project')
        {
            $statistics->consumed       = isset($results['consume_of_task_in_execution'][0])              ? array_sum(array_column($results['consume_of_task_in_execution'], 'value'))              : 0;
            $statistics->bugRate        = isset($results['rate_of_frombug_task_in_execution'][0])         ? array_sum(array_column($results['rate_of_frombug_task_in_execution'], 'value'))         : 0;
            $statistics->bugConsumeRate = isset($results['consume_rate_of_frombug_task_in_execution'][0]) ? array_sum(array_column($results['consume_rate_of_frombug_task_in_execution'], 'value')) : 0;
        }
        $efforts = $this->getTaskEfforts(array_keys($taskList));
        $members = $this->loadModel($type)->getTeamMembers($data->id);
        foreach($efforts as $taskID => $effort)
        {
            if(!isset($members[$effort->account])) unset($efforts[$taskID]);
        }

        $statistics->userPairs = array();
        foreach($members as $member)
        {
            $efforts[] = (object)array(
                'consumed'   => 0,
                'left'       => 0,
                'totalHours' => $member->totalHours,
                'execution'  => $data->id,
                'account'    => $member->account
            );

            $statistics->userPairs[$member->account] = $member->realname;
        }

        $metrics = array((object)array('code' => 'consume_of_task_in_user', 'scope' => 'user', 'purpose' => 'scale'));
        $results = $this->loadModel('metric')->getResultByCodeFromData($metrics, $efforts);

        $statistics->userEfforts = isset($results['consume_of_task_in_user']) ? $results['consume_of_task_in_user'] : array();
        $statistics->teamEfforts = array();

        $sortFields = array();
        foreach($statistics->userEfforts as $index => $userEffort)
        {
            $statistics->userEfforts[$index]['name'] = isset($members[$userEffort['user']]) ? $members[$userEffort['user']]->realname : $userEffort['user'];

            $estimate = isset($members[$userEffort['user']]) ? $members[$userEffort['user']]->totalHours : 0;
            $estimate = (float)$estimate;
            $rate     = ($estimate != 0 && $estimate != 0.0) ? round(($userEffort['value'] / $estimate) * 100, 2) . '%' : '-';
            $statistics->teamEfforts[] = array($statistics->userEfforts[$index]['name'], $estimate, $userEffort['value'], $rate);

            $sortFields[] = $rate;
        }

        array_multisort($sortFields, SORT_DESC, SORT_NUMERIC, $statistics->teamEfforts);
        $statistics->workAssignSummary = $this->buildTaskSummaryTable($taskList, $statistics->userPairs, 'workAssignSummary', $type, $data);
        $statistics->workSummary       = $this->buildTaskSummaryTable($taskList, $statistics->userPairs, 'workSummary', $type, $data);

        return $statistics;
    }

    /**
     * 构建资源分析图表配置。
     * Build resource chart config.
     *
     * @param  array  $taskList
     * @param  object $data     项目|执行
     * @param  string $type
     * @access public
     * @return array
     */
    public function buildResourceChartConfig($taskList, $data, $type = 'execution')
    {
        $this->loadModel('report');
        $metrics  = $this->getResourceMetrics($taskList, $data, $type);
        $settings = array();
        $xAxis    = $this->config->report->reportChart->xAxis;
        $yAxis    = 100;
        foreach(array('taskNum', 'consumed', 'bugTaskNum', 'bugConsume') as $index => $field) $settings = array_merge($settings, $this->report->buildTextChartConfig($metrics->$field, $this->lang->task->report->$field, $xAxis, $yAxis, $index));

        $xAxis  = 0;
        $yAxis += $this->config->report->reportChart->textHeight + $this->config->report->reportChart->padding * 8;
        foreach(array('bugRate', 'bugConsumeRate') as $index => $field) $settings = array_merge($settings, $this->report->buildWaterChartConfig($this->lang->task->report->$field, $metrics->$field, $xAxis, $yAxis, $index, $this->lang->task->report->tips->$field));

        $yAxis   += $this->config->report->reportChart->waterHeight + $this->config->report->reportChart->padding * 9;
        $settings = array_merge($settings, $this->report->buildBarChartConfig($this->lang->task->report->userEfforts, $metrics->userEfforts, $yAxis, 'cluBarY'));

        $yAxis  += $this->config->report->reportChart->barHeight + $this->config->report->reportChart->padding * 4;
        $headers = array(array(
            array('field' => 'name',     'name' => 'name',     'label' => $this->lang->task->report->member),
            array('field' => 'effort',   'name' => 'effort',   'label' => $this->lang->task->report->effort),
            array('field' => 'consumed', 'name' => 'consumed', 'label' => $this->lang->task->report->consumedHour),
            array('field' => 'rate',     'name' => 'rate',     'label' => $this->lang->task->report->consumedRate),
        ));
        $settings = array_merge($settings, $this->report->buildTableChartConfig($this->lang->task->report->teamEfforts, $headers, $metrics->teamEfforts, $yAxis, 4));

        $lineNum = $this->app->tab == 'project' ? 7 : 0;
        foreach(array('workAssignSummary', 'workSummary') as $type)
        {
            $yAxis   += $this->config->report->reportChart->barHeight + $this->config->report->reportChart->padding * 9;
            $tips     = $type == 'workSummary' ? $this->lang->task->report->tips->notFinished : $this->lang->task->report->tips->assigned;
            $settings = array_merge($settings, $this->report->buildTableChartConfig($this->lang->pivot->{$type}, $metrics->{$type}->headers, $metrics->{$type}->dataset, $yAxis, $lineNum, $metrics->{$type}->rowspan, $tips));
        }
        return $settings;
    }

    /**
     * 构建任务汇总表数据结构。
     * Build task summary table data structure.
     *
     * @param  array  $taskList
     * @param  array  $users
     * @param  string $type
     * @access public
     * @return object
     */
    public function buildTaskSummaryTable($taskList, $users, $type = 'workAssignSummary', $from = 'execution', $data = null)
    {
        $isAssign = $type == 'workAssignSummary';
        $summary  = $this->loadModel('pivot')->getTaskSummary($taskList, array_keys($users), strtolower($type));

        $tableData = new stdclass();
        $tableData->dataset = array();
        $tableData->rowspan = array();
        $tableData->headers = array(array(
            array('field' => 'type',          'name' => 'type',          'label' => zget($this->lang->task, $isAssign ? 'assignedTo' : 'finishedByAB')),
            array('field' => 'name',          'name' => 'name',          'label' => $this->lang->task->name),
            array('field' => 'priAB',         'name' => 'priAB',         'label' => $this->lang->pri),
            array('field' => 'estStarted',    'name' => 'estStarted',    'label' => $this->lang->task->estStarted),
            array('field' => 'realStarted',   'name' => 'realStarted',   'label' => $this->lang->task->realStarted),
            array('field' => 'deadline',      'name' => 'deadline',      'label' => $this->lang->task->deadline),
            array('field' => 'delay',         'name' => 'delay',         'label' => $this->lang->pivot->delay . '(' . $this->lang->pivot->day . ')'),
            array('field' => 'estimate',      'name' => 'estimate',      'label' => $this->lang->task->estimate),
            array('field' => 'taskConsumed',  'name' => 'taskConsumed',  'label' => $this->lang->pivot->taskConsumed),
            array('field' => 'taskTotal',     'name' => 'taskTotal',     'label' => $this->lang->pivot->taskTotal),
            array('field' => 'totalConsumed', 'name' => 'totalConsumed', 'label' => $this->lang->pivot->totalConsumed, 'helpIcon' => 'HelpCircleOutline', 'helpIconSize' => 20, 'helpIconColor' => '#52525B', 'helpPosition' => 'end', 'hint' => $this->lang->task->report->tips->totalConsumed)
        ));
        if($from == 'project')
        {
            $name = in_array($data->model, array('waterfall', 'waterfallplus', 'ipd')) ? $this->lang->stage->common : $this->lang->execution->common;
            $tableData->headers = array(array(
                array('field' => 'type',          'name' => 'type',          'label' => zget($this->lang->task, $isAssign ? 'assignedTo' : 'finishedByAB')),
                array('field' => 'execution',     'name' => 'execution',     'label' => sprintf($this->lang->task->report->execution, $name)),
                array('field' => 'taskID',        'name' => 'taskID',        'label' => $this->lang->task->id),
                array('field' => 'name',          'name' => 'name',          'label' => $this->lang->task->name),
                array('field' => 'priAB',         'name' => 'priAB',         'label' => $this->lang->pri),
                array('field' => 'estStarted',    'name' => 'estStarted',    'label' => $this->lang->task->estStarted),
                array('field' => 'realStarted',   'name' => 'realStarted',   'label' => $this->lang->task->realStarted),
                array('field' => 'deadline',      'name' => 'deadline',      'label' => $this->lang->task->deadline),
                array('field' => 'delay',         'name' => 'delay',         'label' => $this->lang->pivot->delay . '(' . $this->lang->pivot->day . ')'),
                array('field' => 'estimate',      'name' => 'estimate',      'label' => $this->lang->task->estimate),
                array('field' => 'taskConsumed',  'name' => 'taskConsumed',  'label' => $this->lang->pivot->taskConsumed),
                array('field' => 'taskTotal',     'name' => 'taskTotal',     'label' => $this->lang->pivot->taskTotal),
                array('field' => 'execConsumed',  'name' => 'execConsumed',  'label' => sprintf($this->lang->task->report->executionConsumed, $name)),
                array('field' => 'totalConsumed', 'name' => 'totalConsumed', 'label' => $this->lang->pivot->totalConsumed, 'helpIcon' => 'HelpCircleOutline', 'helpIconSize' => 20, 'helpIconColor' => '#52525B', 'helpPosition' => 'end', 'hint' => $this->lang->task->report->tips->totalConsumed)
            ));
        }

        $this->loadModel('execution');
        foreach($summary as $user => $projectTaskGroup)
        {
            if(!isset($users[$user])) continue;

            $taskTotal         = $totalConsumed = $userExecutionConsumed = 0;
            $executionConsumed = $executionGroup = array();
            foreach($projectTaskGroup as $executionTasks)
            {
                foreach($executionTasks as $executionID => $tasks)
                {
                    $taskNum = count($tasks);
                    $executionGroup[$executionID] = $taskNum;
                    $taskTotal += $taskNum;
                    foreach($tasks as $task)
                    {
                        if(!isset($executionConsumed[$task->execution])) $executionConsumed[$task->execution] = 0;
                        if($task->isParent != 1)
                        {
                            $executionConsumed[$task->execution] += $task->consumed;
                            $totalConsumed += $task->consumed;
                        }
                    }
                }
            }
            $totalConsumed = round($totalConsumed, 2);

            foreach($projectTaskGroup as $projectID => $executionTasks)
            {
                foreach($executionTasks as $executionID => $tasks)
                {
                    $executionName = $this->execution->getById($executionID)->name;
                    $index         = 0;
                    foreach($tasks as $task)
                    {
                        if($index == 1 && $taskTotal) $taskTotal = 0;

                        $taskName  = $task->name;
                        $prefix    = "[{$this->lang->task->childrenAB}]";
                        $hasPrefix = strpos($taskName, $prefix) !== false;
                        if($task->parent > 0 && !$hasPrefix) $taskName = "$prefix " . $taskName;
                        if($task->multiple)                  $taskName = "[{$this->lang->task->multipleAB}] " . $taskName;

                        $days = '';
                        if(!helper::isZeroDate($task->deadline))
                        {
                            if(!empty($task->deadline) && !helper::isZeroDate($task->deadline))
                            {
                                $finishedDate = ($task->status == 'done' || $task->status == 'closed') && !helper::isZeroDate($task->finishedDate) ? substr($task->finishedDate, 0, 10) : helper::today();
                                $actualDays   = $this->loadModel('holiday')->getActualWorkingDays($task->deadline, $finishedDate);
                                $delay        = !is_array($actualDays) ? 0 : count($actualDays) - 1;
                                if($delay > 0) $days = $delay;
                            }
                        }

                        if($from == 'project')
                        {
                            $tableData->rowspan[] = array($taskTotal, $executionGroup[$executionID], 1, 1, 1, 1, 1, 1, 1, 1, 1, $taskTotal, $executionGroup[$executionID], $taskTotal, $taskTotal);
                            $tableData->dataset[] = array(zget($users, $user), $executionName, $task->id, $taskName, zget($this->lang->task->priList, $task->pri), $task->estStarted, $task->realStarted ? substr($task->realStarted, 0, 10) : '', $task->deadline, $days, $task->estimate, round($task->consumed, 2), $taskTotal, round($executionConsumed[$executionID], 2), $totalConsumed);
                        }
                        else
                        {
                            $tableData->rowspan[] = array($taskTotal, 1, 1, 1, 1, 1, 1, 1, 1, $taskTotal, $taskTotal, $taskTotal);
                            $tableData->dataset[] = array(zget($users, $user), $taskName, zget($this->lang->task->priList, $task->pri), $task->estStarted, $task->realStarted ? substr($task->realStarted, 0, 10) : '', $task->deadline, $days, $task->estimate, round($task->consumed, 2), $taskTotal, $totalConsumed);
                        }
                        $index ++;
                    }
                }
            }
        }

        return $tableData;
    }
}
