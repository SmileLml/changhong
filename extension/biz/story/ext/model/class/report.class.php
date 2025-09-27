<?php
class reportStory extends storyModel
{
    /**
     * 获取变更过的需求。
     * Get the changed story.
     *
     * @param  int  $storyID
     * @param  string $begin
     * @param  string $end
     * @access public
     * @return int
     */
    public function getChangedStory($storyID, $begin, $end)
    {
        $changedNum = $this->dao->select('COUNT(1) AS count')->from(TABLE_ACTION)
            ->where('objectType')->eq('story')
            ->andWhere('objectID')->eq($storyID)
            ->andWhere('action')->eq('changed')
            ->andWhere('date')->ge($begin)
            ->andWhere('date')->lt($end)
            ->fetch('count');

        $recallNum = $this->dao->select('COUNT(1) AS count')->from(TABLE_ACTION)
            ->where('objectType')->eq('story')
            ->andWhere('objectID')->eq($storyID)
            ->andWhere('action')->in('recalledchange,reviewreverted')
            ->andWhere('date')->ge($begin)
            ->andWhere('date')->lt($end)
            ->fetch('count');

        return $changedNum - $recallNum;
    }
    /**
     * 获取基本统计数据。
     * Get basic metrics.
     *
     * @param  array  $storyList
     * @param  object $data      项目|执行
     * @param  string $type
     * @access public
     * @return object
     */
    public function getBasicMetrics($storyList, $data, $type = 'execution')
    {
        $users      = $this->loadModel('user')->getPairs('noletter');
        $moduleList = $this->loadModel('tree')->getAllModulePairs('story');
        $products   = $this->loadModel('product')->getProducts($data->id);
        $startDate  = helper::isZeroDate($data->realBegan) ? $data->begin  : $data->realBegan;
        $endDate    = helper::isZeroDate($data->realEnd)   ? date('Y-m-d') : $data->realEnd;
        $begin      = $startDate . ' 00:00:00';
        $end        = date('Y-m-d 00:00:00', strtotime("$endDate +1 day"));
        $realBegan  = !helper::isZeroDate($data->realBegan);

        $changedNum   = 0;
        $changedScale = 0;
        $this->loadModel('action');
        foreach($storyList as $story)
        {
            $story->executionStartDate = $startDate;
            $story->executionEndDate   = $endDate;
            if(isset($products[$story->product])) $story->product = $products[$story->product]->name;

            $story->secondModule = '';
            if($story->module)
            {
                $moduleName = zget($moduleList, $story->module, '/');
                $modules    = explode('/', trim($moduleName, '/'));
                $story->module = $modules[0];
                if(isset($modules[1])) $story->secondModule = $modules[1];
            }
            if($realBegan && !in_array($story->status, array('reviewing', 'changing')))
            {
                $changeNum = $this->getChangedStory($story->id, $data->realBegan . ' 00:00:00', $end);
                if($changeNum > 0)
                {
                    $changedNum++;
                    if($story->isParent != '1') $changedScale += $story->estimate;
                }
            }
        }

        $metrics = array(
            (object)array('scope' => 'system',  'purpose' => 'scale', 'code' => 'count_of_story'),
            (object)array('scope' => 'system',  'purpose' => 'scale', 'code' => 'scale_of_story'),
            (object)array('scope' => 'product', 'purpose' => 'scale', 'code' => 'count_of_developed_story_in_product'),
            (object)array('scope' => 'product', 'purpose' => 'scale', 'code' => 'scale_of_developed_story_in_product'),
            (object)array('scope' => 'system',  'purpose' => 'scale', 'code' => 'count_of_tested_story'),
            (object)array('scope' => 'system',  'purpose' => 'scale', 'code' => 'scale_of_tested_story'),
            (object)array('scope' => 'system',  'purpose' => 'scale', 'code' => 'count_of_finished_story'),
            (object)array('scope' => 'system',  'purpose' => 'scale', 'code' => 'scale_of_finished_story'),
            (object)array('scope' => 'system',  'purpose' => 'scale', 'code' => 'count_of_closed_story'),
            (object)array('scope' => 'system',  'purpose' => 'scale', 'code' => 'scale_of_closed_story'),
            (object)array('scope' => 'product', 'purpose' => 'scale', 'code' => 'count_of_story_in_product_and_module'),
            (object)array('scope' => 'product', 'purpose' => 'scale', 'code' => 'count_of_story_in_stage_in_product'),
            (object)array('scope' => 'product', 'purpose' => 'scale', 'code' => 'count_of_story_in_status_in_product'),
            (object)array('scope' => 'product', 'purpose' => 'scale', 'code' => 'count_of_story_in_category_in_product'),
            (object)array('scope' => 'product', 'purpose' => 'scale', 'code' => 'count_of_story_in_pri_in_product'),
            (object)array('scope' => 'product', 'purpose' => 'scale', 'code' => 'count_of_story_in_source_in_product'),
            (object)array('scope' => 'user',    'purpose' => 'scale', 'code' => 'count_of_story_in_user'),
        );

        $results = $this->loadModel('metric')->getResultByCodeFromData($metrics, $storyList);

        $statistics = new stdclass();
        $statistics->storyNum     = isset($results['count_of_story'][0])                      ? $results['count_of_story'][0]['value']                                            : 0;
        $statistics->storyScale   = isset($results['scale_of_story'][0])                      ? $results['scale_of_story'][0]['value']                                            : 0;
        $statistics->devNum       = isset($results['count_of_developed_story_in_product'][0]) ? array_sum(array_column($results['count_of_developed_story_in_product'], 'value')) : 0;
        $statistics->devScale     = isset($results['scale_of_developed_story_in_product'][0]) ? array_sum(array_column($results['scale_of_developed_story_in_product'], 'value')) : 0;
        $statistics->testNum      = isset($results['count_of_tested_story'][0])               ? $results['count_of_tested_story'][0]['value']                                     : 0;
        $statistics->testScale    = isset($results['scale_of_tested_story'][0])               ? $results['scale_of_tested_story'][0]['value']                                     : 0;
        $statistics->doneNum      = isset($results['count_of_finished_story'][0])             ? $results['count_of_finished_story'][0]['value']                                   : 0;
        $statistics->doneScale    = isset($results['scale_of_finished_story'][0])             ? $results['scale_of_finished_story'][0]['value']                                   : 0;
        $statistics->closedNum    = isset($results['count_of_closed_story'][0])               ? $results['count_of_closed_story'][0]['value']                                     : 0;
        $statistics->closedScale  = isset($results['scale_of_closed_story'][0])               ? $results['scale_of_closed_story'][0]['value']                                     : 0;
        $statistics->changedNum   = $changedNum;
        $statistics->changedScale = round($changedScale, 2);
        $statistics->storyScale   = round($statistics->storyScale, 2);
        $statistics->devScale     = round($statistics->devScale, 2);
        $statistics->testScale    = round($statistics->testScale, 2);
        $statistics->doneScale    = round($statistics->doneScale, 2);
        $statistics->closedScale  = round($statistics->closedScale, 2);

        $statistics->productMap = $results['count_of_story_in_product_and_module'];

        $stageMetrics    = isset($results['count_of_story_in_stage_in_product'])    ? $results['count_of_story_in_stage_in_product']    : array();
        $statusMetrics   = isset($results['count_of_story_in_status_in_product'])   ? $results['count_of_story_in_status_in_product']   : array();
        $categoryMetrics = isset($results['count_of_story_in_category_in_product']) ? $results['count_of_story_in_category_in_product'] : array();
        $priMetrics      = isset($results['count_of_story_in_pri_in_product'])      ? $results['count_of_story_in_pri_in_product']      : array();
        $sourceMetrics   = isset($results['count_of_story_in_source_in_product'])   ? $results['count_of_story_in_source_in_product']   : array();
        $userMetrics     = isset($results['count_of_story_in_user'])                ? $results['count_of_story_in_user']                : array();

        $chars = array('"', "'", '\\');
        foreach(array('status', 'category', 'pri', 'stage', 'source', 'user') as $field)
        {
            $fieldName = $field . 'List';
            $mapName   = $field . 'Map';
            $$mapName  = array();

            $nameList = $field == 'user' ? $users : $this->lang->story->$fieldName;
            foreach($nameList as $code => $name)
            {
                $name = str_replace($chars, '', $name);
                $$mapName[$code] = array('count' => 0, 'name' => $name);
                foreach(${$field . 'Metrics'} as $metric)
                {
                    if($metric[$field] == $code) $$mapName[$code]['count'] = $metric['value'];
                    if(!isset($$mapName[$code])) $$mapName[$code] = array('name' => $metric[$field], 'count' => $metric['value']);
                }
            }

            if($field == 'user') $$mapName = array_filter($$mapName, function($item) {return $item['count'] > 0;});
            if($field == 'status' && empty($$mapName['changed'])) unset($$mapName['changed']);
            $statistics->$mapName = $$mapName;
        }
        return $statistics;
    }

    /**
     * 构建图表配置。
     * Build chart config.
     *
     * @param  array  $storyList
     * @param  object $data      项目|执行
     * @param  string $type
     * @access public
     * @return array
     */
    public function buildBasicChartConfig($storyList, $data, $type = 'execution')
    {
        $this->loadModel('report');
        $metrics = $this->getBasicMetrics($storyList, $data, $type);

        $settings = array();
        $xAxis    = $this->config->report->reportChart->xAxis;
        $yAxis    = 80;
        foreach($this->config->story->report->basicData['basic'] as $index => $field)
        {
            $numField   = $field . 'Num';
            $scaleField = $field . 'Scale';
            if(!isset($metrics->$numField))   $metrics->$numField   = 0;
            if(!isset($metrics->$scaleField)) $metrics->$scaleField = 0;

            $lineData = array();
            foreach(array($numField, $scaleField) as $fieldName)
            {
                $metric = new stdclass();
                $metric->title = $metrics->$fieldName;
                $metric->desc  = $this->lang->story->report->$fieldName;
                if(isset($this->lang->story->report->tips->$fieldName)) $metric->help = $this->lang->story->report->tips->$fieldName;
                if($fieldName == 'changedNum')
                {
                    $name = $this->app->tab == 'execution' ? $this->lang->execution->common : $this->lang->project->common;
                    $metric->help = str_replace('%s', $name, $metric->help);
                }
                $lineData[] = $metric;
            }

            if($index == 3)
            {
                $xAxis  = $this->config->report->reportChart->xAxis;
                $yAxis += $this->config->report->reportChart->textHeight + $this->config->report->reportChart->padding * 6;
            }
            else
            {
                if($index > 2) $index -= 2;
                if($index) $xAxis += $this->config->report->reportChart->oneThird + $this->config->report->reportChart->padding;
            }

            $settings = array_merge($settings, $this->report->buildTextGroupChartConfig($lineData, $xAxis, $yAxis, $this->config->report->reportChart->oneThird + $this->config->report->reportChart->padding));
        }

        $xAxis     = $this->config->report->reportChart->xAxis;
        $yAxis    += $this->config->report->reportChart->textHeight + $this->config->report->reportChart->padding * 7;
        $barHeight = $this->config->report->reportChart->barHeight + $this->config->report->reportChart->padding * 10;
        $settings  = array_merge($settings, $this->report->buildBarChartConfig($this->lang->story->report->statusMap, $metrics->statusMap, $yAxis, 'cluBarX', 0, 1, $barHeight));

        $yAxis   += $this->config->report->reportChart->barHeight + $this->config->report->reportChart->padding * 9.8;
        $settings = array_merge($settings, $this->report->buildBarChartConfig($this->lang->story->report->stageMap, $metrics->stageMap, $yAxis, 'cluBarX', 0, 1, $barHeight));

        $yAxis   += $this->config->report->reportChart->barHeight + $this->config->report->reportChart->padding * 9.8;
        $settings = array_merge($settings, $this->report->buildSunburstChartConfig($this->lang->story->report->productMap, $metrics->productMap, $xAxis, $yAxis, $this->lang->story->report->tips->productMap));

        $yAxis += $this->config->report->reportChart->oneHalf + $this->config->report->reportChart->padding * 4.5;
        foreach(array('source', 'pri', 'category', 'user') as $index => $field) $settings = array_merge($settings, $this->report->buildPieChartConfig($this->lang->story->report->{$field . 'Map'}, $metrics->{$field . 'Map'}, $xAxis, $yAxis, $index, 2, $this->lang->story->report->storyNum));
        return $settings;
    }

    /**
     * 获取进度分析数据。
     * Get progress metrics.
     *
     * @param  array  $storyList
     * @access public
     * @return object
     */
    public function getProgressMetrics($storyList)
    {
        $metrics = array(
            (object)array('scope' => 'system',  'purpose' => 'scale', 'code' => 'count_of_story'),
            (object)array('scope' => 'system',  'purpose' => 'scale', 'code' => 'scale_of_story'),
            (object)array('scope' => 'system',  'purpose' => 'scale', 'code' => 'count_of_finished_story'),
            (object)array('scope' => 'system',  'purpose' => 'scale', 'code' => 'scale_of_finished_story'),
            (object)array('scope' => 'product', 'purpose' => 'scale', 'code' => 'count_of_developed_story_in_product'),
            (object)array('scope' => 'product', 'purpose' => 'scale', 'code' => 'scale_of_developed_story_in_product'),
            (object)array('scope' => 'system',  'purpose' => 'scale', 'code' => 'count_of_tested_story'),
            (object)array('scope' => 'system',  'purpose' => 'scale', 'code' => 'scale_of_tested_story'),
            (object)array('scope' => 'product', 'purpose' => 'scale', 'code' => 'count_of_story_in_stage_in_product'),
        );
        $results = $this->loadModel('metric')->getResultByCodeFromData($metrics, $storyList);

        /* 需求数、需求规模数、已完成需求数、已完成需求规模数、研发完成需求数、研发完毕需求规模数、测试完毕需求数、测试完毕需求规模数 */
        /* Number of requirements, size of requirements, number of completed requirements, size of completed requirements, number of requirements completed, size of requirements completed, number of requirements completed in testing, size of requirements completed in testing. */
        $statistics = new stdclass();
        $statistics->storyNum   = isset($results['count_of_story'][0])                      ? $results['count_of_story'][0]['value']                                            : 0;
        $statistics->storyScale = isset($results['scale_of_story'][0])                      ? $results['scale_of_story'][0]['value']                                            : 0;
        $statistics->doneNum    = isset($results['count_of_finished_story'][0])             ? $results['count_of_finished_story'][0]['value']                                   : 0;
        $statistics->doneScale  = isset($results['scale_of_finished_story'][0])             ? $results['scale_of_finished_story'][0]['value']                                   : 0;
        $statistics->devNum     = isset($results['count_of_developed_story_in_product'][0]) ? array_sum(array_column($results['count_of_developed_story_in_product'], 'value')) : 0;
        $statistics->devScale   = isset($results['scale_of_developed_story_in_product'][0]) ? array_sum(array_column($results['scale_of_developed_story_in_product'], 'value')) : 0;
        $statistics->testNum    = isset($results['count_of_tested_story'][0])               ? $results['count_of_tested_story'][0]['value']                                     : 0;
        $statistics->testScale  = isset($results['scale_of_tested_story'][0])               ? $results['scale_of_tested_story'][0]['value']                                     : 0;
        $statistics->storyScale = round($statistics->storyScale, 2);
        $statistics->devScale   = round($statistics->devScale, 2);
        $statistics->testScale  = round($statistics->testScale, 2);
        $statistics->doneScale  = round($statistics->doneScale, 2);

        /* 按条目/规模统计的需求研发完毕率、需求测试完毕率、需求完成率 */
        /* Requirements development completion rate, requirements testing completion rate, requirements completion rate by entry or size. */
        $statistics->devRate       = (empty($statistics->devNum) || empty($statistics->storyNum)) ? 0 : round($statistics->devNum / $statistics->storyNum, 4);
        $statistics->devScaleRate  = (empty($statistics->devScale) || empty($statistics->storyScale)) ? 0 : round($statistics->devScale / $statistics->storyScale, 4);
        $statistics->testRate      = (empty($statistics->testNum) || empty($statistics->storyNum)) ? 0 : round($statistics->testNum / $statistics->storyNum, 4);
        $statistics->testScaleRate = (empty($statistics->testScale) || empty($statistics->storyScale)) ? 0 : round($statistics->testScale / $statistics->storyScale, 4);
        $statistics->doneRate      = (empty($statistics->doneNum) || empty($statistics->storyNum)) ? 0 : round($statistics->doneNum / $statistics->storyNum, 4);
        $statistics->doneScaleRate = (empty($statistics->doneScale) || empty($statistics->storyScale)) ? 0 : round($statistics->doneScale / $statistics->storyScale, 4);

        /* 需求阶段，顺序：研发立项、设计中、设计完毕、研发中、研发完毕、测试中、测试完毕、已验收、验收失败、交付中、已交付、已发布、已关闭、空。 */
        /* Requirement stage, sequence: project initiation, design in progress, design completed, in progress, completed, testing in progress, testing completed, accepted, acceptance failed, delivery in progress, delivered, released, closed, empty. */
        $stageMetrics = isset($results['count_of_story_in_stage_in_product']) ? $results['count_of_story_in_stage_in_product'] : array();
        $stageList    = $this->lang->story->stageList;

        unset($stageList['wait']);
        unset($stageList['planned']);
        $statistics->stageMap = array();
        foreach($stageList as $stage => $stageName) $statistics->stageMap[$stage] = array('count' => 0, 'name' => $stageName);
        foreach($stageMetrics as $item)
        {
            $stage = $item['stage'];
            if(isset($statistics->stageMap[$stage])) $statistics->stageMap[$stage]['count'] = $item['value'];
        }

        return $statistics;
    }

    /**
     * 构建进度分析配置。
     * Build chart config.
     *
     * @param  array  $storyList
     * @access public
     * @return array
     */
    public function buildProgressChartConfig($storyList)
    {
        $this->loadModel('report');
        $settings = array();
        $metrics  = $this->getProgressMetrics($storyList);
        $xAxis    = $this->config->report->reportChart->xAxis;
        $yAxis    = 80;
        foreach($this->config->story->report->progressData['progress'] as $index => $field)
        {
            $numField   = $field . 'Num';
            $scaleField = $field . 'Scale';
            if(!isset($metrics->$numField))   $metrics->$numField   = 0;
            if(!isset($metrics->$scaleField)) $metrics->$scaleField = 0;

            $lineData = array();
            foreach(array($numField, $scaleField) as $fieldName)
            {
                $metric = new stdclass();
                $metric->title = $metrics->$fieldName;
                $metric->desc  = $this->lang->story->report->$fieldName;
                $lineData[] = $metric;
            }

            if($index == 2)
            {
                $xAxis  = $this->config->report->reportChart->xAxis;
                $yAxis += $this->config->report->reportChart->textHeight + $this->config->report->reportChart->padding * 6;
            }
            else
            {
                if($index) $xAxis += $this->config->report->reportChart->oneHalf + $this->config->report->reportChart->padding;
            }

            $settings = array_merge($settings, $this->report->buildTextGroupChartConfig($lineData, $xAxis, $yAxis, $this->config->report->reportChart->oneHalf + $this->config->report->reportChart->padding));
        }

        $yAxis += $this->config->report->reportChart->waterHeight + $this->config->report->reportChart->padding * 5.3;
        $xAxis  = $this->config->report->reportChart->xAxis - 20;
        foreach(array('devRate', 'testRate', 'doneRate') as $index => $field) $settings = array_merge($settings, $this->report->buildWaterChartConfig($this->lang->story->report->$field, $metrics->$field, $xAxis, $yAxis, $index, $this->lang->story->report->tips->$field, 3));
        $yAxis += $this->config->report->reportChart->waterHeight + $this->config->report->reportChart->padding * 11.5;
        $xAxis  = $this->config->report->reportChart->xAxis - 20;
        foreach(array('devScaleRate', 'testScaleRate', 'doneScaleRate') as $index => $field) $settings = array_merge($settings, $this->report->buildWaterChartConfig($this->lang->story->report->$field, $metrics->$field, $xAxis, $yAxis, $index, $this->lang->story->report->tips->$field, 3));

        $xAxis     = $this->config->report->reportChart->xAxis;
        $yAxis    += $this->config->report->reportChart->waterHeight + $this->config->report->reportChart->padding * 8.5;
        $barHeight = $this->config->report->reportChart->barHeight + $this->config->report->reportChart->padding * 10;
        $settings  = array_merge($settings, $this->report->buildBarChartConfig($this->lang->story->report->stageMap, $metrics->stageMap, $yAxis, 'cluBarX', 0, 1, $barHeight));
        $yAxis    += $barHeight + $this->config->report->reportChart->padding * 20;

        return $settings;
    }
}
