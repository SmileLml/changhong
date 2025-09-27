<?php
/**
 * 获取基本统计数据。
 * Get basic metrics.
 *
 * @param  array  $executions
 * @param  object $project
 * @access public
 * @return object
 */
public function getExecutionBasicMetrics($executions, $project)
{
    $metrics = array(
        (object)array('scope' => 'project',   'purpose' => 'scale', 'code' => 'count_of_doing_execution_in_project'),
        (object)array('scope' => 'project',   'purpose' => 'scale', 'code' => 'count_of_closed_execution_in_project'),
        (object)array('scope' => 'project',   'purpose' => 'scale', 'code' => 'count_of_delayed_execution_in_project'),
        (object)array('scope' => 'execution', 'purpose' => 'scale', 'code' => 'count_of_bug_in_status_execution'),
    );
    $results = $this->loadModel('metric')->getResultByCodeFromData($metrics, $executions);

    $statistics = new stdclass();
    $statistics->total      = count($executions);
    $statistics->doingNum   = isset($results['count_of_doing_execution_in_project'][0]) ? $results['count_of_doing_execution_in_project'][0]['value'] : 0;
    $statistics->closedNum  = isset($results['count_of_closed_execution_in_project'][0]) ? $results['count_of_closed_execution_in_project'][0]['value'] : 0;
    $statistics->delayNum   = isset($results['count_of_delayed_execution_in_project'][0]) ? $results['count_of_delayed_execution_in_project'][0]['value'] : 0;
    $statistics->closedRate = empty($statistics->total) ? 0 : round($statistics->closedNum / $statistics->total, 4);
    $statistics->delayRate  = empty($statistics->total) ? 0 : round($statistics->delayNum / $statistics->total, 4);

    $statusMetrics = isset($results['count_of_bug_in_status_execution']) ? $results['count_of_bug_in_status_execution'] : array();

    $resultName = array();
    $chars      = array('"', "'", '\\');
    foreach($this->lang->execution->statusList as $code => $name)
    {
        $name = str_replace($chars, '', $name);
        $resultName[$code] = array('count' => 0, 'name' => $name);
        foreach($statusMetrics as $metric)
        {
            if($metric['status'] == $code) $resultName[$code]['count'] = $metric['value'];
            if(!isset($resultName[$code])) $resultName[$code] = array('name' => $metric['status'], 'count' => $metric['value']);
        }
    }
    $statistics->statusMap     = $resultName;
    $statistics->doingSummary  = $this->buildExecutionSummaryTable($executions, $project, 'doingSummary');
    $statistics->closedSummary = $this->buildExecutionSummaryTable($executions, $project, 'closedSummary');

    return $statistics;
}

/**
 * 构建执行的基本统计配置。
 * Build chart config.
 *
 * @param  array  $bugs
 * @param  object $execution
 * @access public
 * @return array
 * @param mixed[] $executions
 * @param object $project
 */
public function buildBasicExecutionConfig($executions, $project)
{
    $this->loadModel('report');
    $settings = array();
    $metrics  = $this->getExecutionBasicMetrics($executions, $project);
    $xAxis    = $this->config->report->reportChart->xAxis;
    $yAxis    = 80;
    $name     = in_array($project->model, array('waterfall', 'waterfallplus', 'ipd')) ? $this->lang->stage->common : $this->lang->project->reportSettings->execution;
    $width    = $this->config->report->reportChart->oneQuarter;
    foreach(array('total', 'doingNum', 'closedNum', 'delayNum') as $index => $field)
    {
        $fieldName = sprintf($this->lang->project->reportSettings->$field, $name);
        $settings  = array_merge($settings, $this->report->buildTextChartConfig($metrics->$field, $fieldName, $xAxis, $yAxis, $index, $width));
    }

    $yAxis += $this->config->report->reportChart->textHeight + $this->config->report->reportChart->padding * 8;
    $xAxis  = 0;
    foreach(array('closedRate', 'delayRate') as $index => $field)
    {
        $fieldName = str_replace('%s', $name, $this->lang->project->reportSettings->$field);
        $fieldTips = str_replace('%s', $name, $this->lang->project->reportSettings->tips->$field);
        $settings  = array_merge($settings, $this->report->buildWaterChartConfig($fieldName, $metrics->$field,  $xAxis, $yAxis, $index, $fieldTips, 2));
    }

    $yAxis      += $this->config->report->reportChart->waterHeight + $this->config->report->reportChart->padding * 9;
    $xAxis       = 0;
    $statusTitle = sprintf($this->lang->project->reportSettings->statusMap, $name);
    $settings    = array_merge($settings, $this->report->buildBarChartConfig($statusTitle, $metrics->statusMap, $yAxis));

    $yAxis -= $this->config->report->reportChart->padding * 4.6;
    foreach(array('doingSummary', 'closedSummary') as $type)
    {
        $yAxis   += $this->config->report->reportChart->barHeight + $this->config->report->reportChart->padding * 9;
        $title    = sprintf($this->lang->project->reportSettings->$type, $name);
        $tips     = str_replace('%s', $name, $this->lang->project->reportSettings->tips->$type);
        $settings = array_merge($settings, $this->report->buildTableChartConfig($title, $metrics->{$type}->headers, $metrics->{$type}->dataset, $yAxis, 0, array(), '', $tips));
    }

    return $settings;
}

/**
 * 构建进行中迭代汇总表。
 * Build an ongoing execution summary table.
 *
 * @param  array  $executions
 * @param  object $project
 * @access public
 * @return object
 * @param string $type
 */
public function buildExecutionSummaryTable($executions, $project, $type)
{
    $name          = in_array($project->model, array('waterfall', 'waterfallplus', 'ipd')) ? $this->lang->stage->common : $this->lang->project->reportSettings->execution;
    $executionName = sprintf($this->lang->project->reportSettings->name, $name);
    $progressName  = sprintf($this->lang->project->reportSettings->progress, $name);

    $tableData = new stdclass();
    $tableData->dataset = $this->loadModel('pivot')->getExecutionSummary($executions, $type);
    $tableData->rowspan = array();
    $tableData->headers = array(array(
        array('field' => 'execution',   'name' => 'execution',   'label' => $executionName),
        array('field' => 'stories',     'name' => 'stories',     'label' => $this->lang->project->reportSettings->storyNum),
        array('field' => 'undoneStory', 'name' => 'undoneStory', 'label' => $this->lang->project->reportSettings->undoneStory),
        array('field' => 'tasks',       'name' => 'tasks',       'label' => $this->lang->project->reportSettings->taskNum),
        array('field' => 'undoneTask',  'name' => 'undoneTask',  'label' => $this->lang->project->reportSettings->undoneTask),
        array('field' => 'left',        'name' => 'left',        'label' => $this->lang->project->reportSettings->left),
        array('field' => 'consumed',    'name' => 'consumed',    'label' => $this->lang->project->reportSettings->consumed),
        array('field' => 'progress',    'name' => 'progress',    'label' => $progressName),
    ));
    if($type == 'closedSummary')
    {
        $tableData->headers = array(array(
            array('field' => 'execution',     'name' => 'execution',     'label' => $executionName),
            array('field' => 'devRate',       'name' => 'devRate',       'label' => $this->lang->project->reportSettings->devRate),
            array('field' => 'passRate',      'name' => 'passRate',      'label' => $this->lang->project->reportSettings->passRate),
            array('field' => 'storyDoneRate', 'name' => 'storyDoneRate', 'label' => $this->lang->project->reportSettings->storyDoneRate),
            array('field' => 'devDoneRate',   'name' => 'devDoneRate',   'label' => $this->lang->project->reportSettings->devDoneRate),
            array('field' => 'testDoneRate',  'name' => 'testDoneRate',  'label' => $this->lang->project->reportSettings->testDoneRate),
            array('field' => 'testDensity',   'name' => 'testDensity',   'label' => $this->lang->project->reportSettings->testDensity),
        ));
    }

    return $tableData;
}
