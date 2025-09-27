<?php
class story extends control
{
    /**
     * 导出需求报表。
     * Export story report.
     *
     * @param  int    $executionID
     * @param  int    $productID
     * @param  string $browseType
     * @param  int    $param
     * @param  string $type
     * @access public
     * @return void
     */
    public function exportChart($executionID, $productID = 0, $browseType = '', $param = 0, $type = 'basic')
    {
        if(!common::hasPriv('report', 'export')) $this->loadModel('common')->deny('report', 'export', false);

        if($_POST)
        {
            $data      = new stdClass();
            $storyList = array();
            if($this->app->tab == 'execution')
            {
                $data      = $this->loadModel('execution')->getByID($executionID);
                $storyList = $this->loadModel('story')->getExecutionStories($executionID, 0, 'order_desc', $browseType, (string)$param, 'story');
            }
            elseif($this->app->tab == 'project')
            {
                $data      = $this->loadModel('project')->getByID($executionID);
                $storyList = $this->loadModel('story')->getExecutionStories($executionID, $productID, 'order_desc', $browseType, (string)$param, 'all');
            }

            $metrics      = array();
            $configMethod = 'get' . ucfirst($type) . 'Metrics';
            if(method_exists($this->story, $configMethod)) $metrics = $this->story->$configMethod($storyList, $data);

            $items  = $this->config->story->report->exportFields[$type];
            $charts = $this->post->charts;
            $index  = 0;
            $datas  = $images = array();
            foreach($items as $key => $item)
            {
                $title = zget($this->lang->story->report, $key, '');
                if(empty($title)) $title = zget($this->lang->story->report->typeList, $key, '');
                $this->lang->story->report->charts[$key] = $title;

                if(is_array($item))
                {
                    $datas[$key] = array();
                    foreach($item as $subItem)
                    {
                        $item = new stdclass();
                        $item->name  = $this->lang->story->report->$subItem;
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
                        $item->name  = $this->lang->story->report->$key;
                        $item->value = $this->lang->noData;
                        $datas[$key][] = $item;
                    }
                    else
                    {
                        $tableData = array();
                        foreach($this->config->story->report->tableHeaders[$key] as $head) $tableData[0][] = $this->lang->story->report->$head;
                        foreach($metrics->$key as $metric) $tableData[] = $metric;
                        $datas[$key] = $tableData;
                    }
                }
            }

            $this->post->set('datas',  $datas);
            $this->post->set('items',  array_keys($items));
            $this->post->set('images', $images);
            $this->post->set('kind',   'story');
            $this->app->loadLang('report');
            return $this->fetch('file', 'export2chart', $_POST);
        }

        $this->display('task', 'exportchart');
    }
}
