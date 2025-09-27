<?php
class testcase extends control
{
    /**
     * 导出用例报表。
     * Export testcase report.
     *
     * @param  int    $projectID
     * @param  string $type
     * @access public
     * @return void
     */
    public function exportChart($projectID, $type = 'basic')
    {
        if(!common::hasPriv('report', 'export')) $this->loadModel('common')->deny('report', 'export', false);

        if($_POST)
        {
            $tab     = $this->app->tab;
            $project = $this->loadModel($tab)->getByID($projectID);
            $list    = $this->testcase->getExecutionCases('all', $projectID);
            if($tab == 'project')
            {
                $productID = 0;
                if(!$project->hasProduct)
                {
                    $productPairs = $this->loadModel('product')->getProductPairsByProject($projectID);
                    $productID    = key($productPairs);
                }
                $list = $this->testcase->getTestCases($productID, 0, 'all', 0, 0);
            }
            else
            {
                $list = $this->testcase->getExecutionCases('all', $projectID);
            }

            $metrics      = array();
            $configMethod = 'get' . ucfirst($type) . 'Metrics';
            if(method_exists($this->testcase, $configMethod)) $metrics = $this->testcase->$configMethod($list, $project);

            $items    = $this->config->testcase->reportChart->exportFields[$type];
            $charts   = $this->post->charts;
            $index    = 0;
            $datas    = $images = array();
            $typeList = $this->lang->testcase->report->typeList;
            foreach($items as $key => $item)
            {
                $title = zget($this->lang->testcase->report, $key, '');
                if(empty($title))                                 $title = zget($typeList, $key, '');
                if(!isset($this->lang->testcase->report->charts)) $this->lang->testcase->report->charts = array();
                $this->lang->testcase->report->charts[$key] = $title;

                if(is_array($item))
                {
                    $datas[$key] = array();
                    foreach($item as $subItem)
                    {
                        $item = new stdclass();
                        $item->name  = $this->lang->testcase->report->$subItem;
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
                            $item->name  = $this->lang->testcase->report->$key;
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
            $this->post->set('kind',   'testcase');
            $this->app->loadLang('report');
            return $this->fetch('file', 'export2chart', $_POST);
        }
        $this->display('task', 'exportchart');
    }
}
