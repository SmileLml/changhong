<?php
class project extends control
{
    /**
     * 导出报表。
     * Export report.
     *
     * @param  int    $projectID
     * @param  string $type
     * @access public
     * @return void
     * @param string $from
     */
    public function exportChart($projectID, $type = 'basic', $from = 'execution')
    {
        if(!common::hasPriv('report', 'export')) $this->loadModel('common')->deny('report', 'export', false);

        if($_POST)
        {
            $reportLang = new stdclass();
            $typeList   = $items = $statusList = array();

            $project = $this->project->getByID($projectID);
            $metrics = array();
            if($from == 'bug')
            {
                $this->loadModel('execution');
                $list         = $this->loadModel('bug')->getProjectBugs($projectID);
                $configMethod = 'getBug' . ucfirst($type) . 'Metrics';
                if(method_exists($this->execution, $configMethod)) $metrics = $this->execution->$configMethod($list, $project, 'project');
                $reportLang   = $this->lang->execution->report->bug;
                $typeList     = $this->lang->execution->report->typeList['bug'];
                $items        = $this->config->execution->reportChart->exportFields['bug'][$type];
                $statusList   = $this->lang->bug->statusList;
            }
            elseif($from == 'execution')
            {
                if($type == 'basic')
                {
                    $list         = $this->loadModel('execution')->getList($projectID);
                    $configMethod = 'getExecution' . ucfirst($type) . 'Metrics';
                    if(method_exists($this->projectZen, $configMethod)) $metrics = $this->projectZen->$configMethod($list, $project);
                    $reportLang   = $this->lang->project->reportSettings;
                    $typeList     = $this->lang->project->reportSettings->typeList['execution'];
                    $items        = $this->config->project->reportChart->exportFields['execution'][$type];
                    $tableHeaders = $this->config->project->reportChart->tableHeaders;
                    $name         = in_array($project->model, array('waterfall', 'waterfallplus', 'ipd')) ? $this->lang->stage->common : $this->lang->project->reportSettings->execution;
                    $statusList   = $this->lang->execution->statusList;

                    foreach($reportLang as $key => $value)
                    {
                        if(is_string($value) && strpos($value, '%s') !== false) $reportLang->$key = sprintf($value, $name);
                    }
                }
                else
                {
                    $this->loadModel('task');
                    $this->lang->task->report->doneNum  = sprintf($this->lang->task->report->doneNum,  zget($this->lang->task->statusList, 'done'));
                    $this->lang->task->report->devRate  = sprintf($this->lang->task->report->devRate,  zget($this->lang->task->typeList,   'devel'));
                    $this->lang->task->report->testRate = sprintf($this->lang->task->report->testRate, zget($this->lang->task->typeList,   'test'));

                    $this->lang->task->report->tips->notFinished = sprintf($this->lang->task->report->tips->notFinished, zget($this->lang->task->statusList, 'done'));
                    $this->lang->task->report->tips->doneRate    = sprintf($this->lang->task->report->tips->doneRate,    zget($this->lang->task->statusList, 'done'));
                    $this->lang->task->report->tips->devRate     = sprintf($this->lang->task->report->tips->devRate,     zget($this->lang->task->typeList, 'devel'), zget($this->lang->task->statusList, 'done'));
                    $this->lang->task->report->tips->testRate    = sprintf($this->lang->task->report->tips->testRate,    zget($this->lang->task->typeList, 'test'),  zget($this->lang->task->statusList, 'done'));

                    $tasks        = $this->task->getProjectTaskList($project->id);
                    $useType      = $type == 'task' ? 'basic' : $type;
                    $configMethod = 'get' . ucfirst($useType) . 'Metrics';
                    $metrics      = $this->task->$configMethod($tasks, $project, 'project');
                    $reportLang   = $this->lang->task->report;
                    $typeList     = $this->lang->task->report->typeList;
                    $items        = $this->config->task->reportChart->exportFields[$useType];
                    $tableHeaders = $this->config->task->reportChart->tableHeaders;
                    $statusList   = $this->lang->task->statusList;
                }
            }

            $charts = $this->post->charts;
            $index  = 0;
            $datas  = $images = array();
            foreach($items as $key => $item)
            {
                $title = zget($reportLang, $key, '');
                if(empty($title)) $title = zget($typeList, $key, '');
                if($type == 'basic')
                {
                    if(!isset($this->lang->execution->report->charts)) $this->lang->execution->report->charts = array();
                    $this->lang->execution->report->charts[$key] = $title;
                }
                else
                {
                    if(!isset($this->lang->task->report->charts)) $this->lang->task->report->charts = array();
                    $this->lang->task->report->charts[$key] = $title;
                }

                if(is_array($item))
                {
                    $datas[$key] = array();
                    foreach($item as $subItem)
                    {
                        $item = new stdclass();
                        $item->name  = $reportLang->$subItem;
                        $item->value = zget($metrics, $subItem, 0);

                        $datas[$key][] = $item;
                    }
                }
                elseif($item == 'image')
                {
                    if($key == 'productMap')
                    {
                        if($metrics->$key)
                        {
                            $images[$key] = $charts[$index];
                            $index ++;
                        }
                        else
                        {
                            $item = new stdclass();
                            $item->name  = $reportLang->$key;
                            $item->value = $this->lang->noData;
                            $datas[$key][] = $item;
                        }
                    }
                    else
                    {
                        $images[$key] = $charts[$index];
                        $index ++;
                    }
                }
                elseif($item == 'table')
                {
                    $metricData = $type == 'basic' ? $metrics->$key->dataset : $metrics->$key;
                    if(empty($metricData))
                    {
                        $item = new stdclass();
                        $item->name  = $reportLang->$key;
                        $item->value = $this->lang->noData;
                        $datas[$key][] = $item;
                    }
                    else
                    {
                        $tableData = array();
                        foreach($tableHeaders[$key] as $head)
                        {
                            if($head == 'status')
                            {
                                foreach($statusList as $statusName) $tableData[0][] = $statusName;
                                continue;
                            }

                            $tableData[0][] = $reportLang->$head;
                        }

                        foreach($metricData as $metric) $tableData[] = $metric;
                        $datas[$key] = $tableData;
                    }
                    
                }
                elseif($item == 'multiTable')
                {
                    $metric = $metrics->$key;
                     if(empty($metric->dataset))
                    {
                        $item = new stdclass();
                        $item->name  = $reportLang->$key;
                        $item->value = $this->lang->noData;
                        $datas[$key][] = $item;
                    }
                    else
                    {
                        $tableData = array();
                        foreach($metric->headers[0] as $head) $tableData[0][] = $head['label'];
                        foreach($metric->dataset as $i => $data)
                        {
                            $tableData[$i + 1] = array();

                            $row = array();
                            foreach($data as $j => $value)
                            {
                                $row['value']   = $value;
                                $row['rowspan'] = $metric->rowspan[$i][$j];
                                $tableData[$i + 1][] = $row;
                            }
                        }
                        $datas[$key] = $tableData;
                    }
                }
            }

            $this->post->set('datas',  $datas);
            $this->post->set('items',  array_keys($items));
            $this->post->set('images', $images);
            $this->post->set('kind',   $type == 'basic' ? 'execution' : 'task');
            $this->app->loadLang('report');
            return $this->fetch('file', 'export2chart', $_POST);
        }

        $this->display('task', 'exportchart');
    }
}
