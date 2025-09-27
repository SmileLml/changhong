<?php
class execution extends control
{
    /**
     * 导出报表。
     * Export report.
     *
     * @param  int    $executionID
     * @param  string $browseType  bug | testcase
     * @param  int    $param
     * @param  string $type
     * @access public
     * @return void
     */
    public function exportChart($executionID, $browseType = 'bug', $type = 'basic')
    {
        if(!common::hasPriv('report', 'export')) $this->loadModel('common')->deny('report', 'export', false);

        if($_POST)
        {
            $execution = $this->loadModel('execution')->getByID($executionID);
            $list      = $this->loadModel('bug')->getExecutionBugs($executionID);
            if($browseType == 'testcase') $list = $this->loadModel('testcase')->getExecutionCases('all', $executionID);

            $metrics      = array();
            $configMethod = 'get' . ucfirst($browseType) . ucfirst($type) . 'Metrics';
            if(method_exists($this->execution, $configMethod)) $metrics = $this->execution->$configMethod($list, $execution);

            $items    = $this->config->execution->reportChart->exportFields[$browseType][$type];
            $charts   = $this->post->charts;
            $index    = 0;
            $datas    = $images = array();
            $typeList = $this->lang->execution->report->typeList[$browseType];
            foreach($items as $key => $item)
            {
                $title = zget($this->lang->execution->report->$browseType, $key, '');
                if(empty($title))                                    $title = zget($typeList, $key, '');
                if(!isset($this->lang->$browseType->report))         $this->lang->$browseType->report = new stdclass();
                if(!isset($this->lang->$browseType->report->charts)) $this->lang->$browseType->report->charts = array();
                $this->lang->$browseType->report->charts[$key] = $title;

                if(is_array($item))
                {
                    $datas[$key] = array();
                    foreach($item as $subItem)
                    {
                        $item = new stdclass();
                        $item->name  = $this->lang->execution->report->$browseType->$subItem;
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
                            $item->name  = $this->lang->execution->report->$browseType->$key;
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
            }

            $this->post->set('datas',  $datas);
            $this->post->set('items',  array_keys($items));
            $this->post->set('images', $images);
            $this->post->set('kind',   $browseType);
            $this->app->loadLang('report');
            return $this->fetch('file', 'export2chart', $_POST);
        }

        $this->display();
    }
}
