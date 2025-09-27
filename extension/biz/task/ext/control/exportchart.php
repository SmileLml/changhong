<?php
class task extends control
{
    /**
     * 导出任务报表。
     * Export task report.
     *
     * @param  int    $executionID
     * @param  string $browseType
     * @param  int    $param
     * @param  string $type
     * @access public
     * @return void
     */
    public function exportChart($executionID, $browseType = '', $param = 0, $type = 'basic')
    {
        if(!common::hasPriv('report', 'export')) $this->loadModel('common')->deny('report', 'export', false);

        if($_POST)
        {
            $this->lang->task->report->doneNum  = sprintf($this->lang->task->report->doneNum,  zget($this->lang->task->statusList, 'done'));
            $this->lang->task->report->devRate  = sprintf($this->lang->task->report->devRate,  zget($this->lang->task->typeList,   'devel'));
            $this->lang->task->report->testRate = sprintf($this->lang->task->report->testRate, zget($this->lang->task->typeList,   'test'));

            $this->lang->task->report->tips->doneRate = sprintf($this->lang->task->report->tips->doneRate, zget($this->lang->task->statusList, 'done'));
            $this->lang->task->report->tips->devRate  = sprintf($this->lang->task->report->tips->devRate,   zget($this->lang->task->typeList, 'devel'), zget($this->lang->task->statusList, 'done'));
            $this->lang->task->report->tips->testRate = sprintf($this->lang->task->report->tips->testRate,  zget($this->lang->task->typeList, 'test'),  zget($this->lang->task->statusList, 'done'));

            $execution = $this->loadModel('execution')->getByID($executionID);
            $taskList  = $this->taskZen->getReportTaskList($execution, $browseType, $param);

            $metrics       = array();
            $configMethod  = 'get' . ucfirst($type) . 'Metrics';
            if(method_exists($this->task, $configMethod)) $metrics = $this->task->$configMethod($taskList, $execution);

            $items  = $this->config->task->reportChart->exportFields[$type];
            $charts = $this->post->charts;
            $index  = 0;
            $datas  = $images = array();
            foreach($items as $key => $item)
            {
                $title = zget($this->lang->task->report, $key, '');
                if(empty($title)) $title = zget($this->lang->task->report->typeList, $key, '');
                $this->lang->task->report->charts[$key] = $title;

                if(is_array($item))
                {
                    $datas[$key] = array();
                    foreach($item as $subItem)
                    {
                        $item = new stdclass();
                        $item->name  = $this->lang->task->report->$subItem;
                        $item->value = zget($metrics, $subItem, 0);

                        $datas[$key][] = $item;
                    }
                }
                elseif($item == 'image')
                {
                    $images[$key] = $charts[$index];

                    $index ++;
                }
                elseif($item == 'table')
                {
                    if(empty($metrics->$key))
                    {
                        $item = new stdclass();
                        $item->name  = $this->lang->task->report->$key;
                        $item->value = $this->lang->noData;
                        $datas[$key][] = $item;
                    }
                    else
                    {
                        $tableData = array();
                        foreach($this->config->task->reportChart->tableHeaders[$key] as $head)
                        {
                            if($head == 'status')
                            {
                                foreach($this->lang->task->statusList as $statusName) $tableData[0][] = $statusName;
                                continue;
                            }

                            $tableData[0][] = $this->lang->task->report->$head;
                        }
                        foreach($metrics->$key as $metric) $tableData[] = $metric;
                        $datas[$key] = $tableData;
                    }
                }
                elseif($item == 'multiTable')
                {
                    $metric = $metrics->$key;
                    if(empty($metric->dataset))
                    {
                        $item = new stdclass();
                        $item->name  = $this->lang->task->report->$key;
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
            $this->post->set('kind',   'task');
            $this->app->loadLang('report');
            return $this->fetch('file', 'export2chart', $_POST);
        }

        $this->display();
    }
}
