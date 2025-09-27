<?php
class ipdMy extends myModel
{
    /**
     * 构建任务搜索表单。
     * Build search form for task page of work.
     *
     * @param  int    $queryID
     * @param  string $actionURL
     * @param  string $module
     * @param  bool   $cacheSearchFunc 是否缓存构造搜索参数的方法。默认缓存可以提高性能，构造搜索表单时再加载真实值。
     * @access public
     * @return array
     */
    public function buildTaskSearchForm($queryID, $actionURL, $module, $cacheSearchFunc = true)
    {
        $this->loadModel('execution');
        $this->loadModel('marketresearch');
        $this->app->loadLang('researchtask');
        $this->app->loadLang('marketreport');

        $searchConfig = $this->config->execution->search;
        $searchConfig['module']                        = $module;
        $searchConfig['actionURL']                     = $actionURL;
        $searchConfig['queryID']                       = $queryID;
        $searchConfig['fields']['name']                = $this->lang->researchtask->name;
        $searchConfig['fields']['project']             = $this->lang->marketreport->research;
        $searchConfig['fields']['execution']           = $this->lang->marketresearch->execution;
        $searchConfig['params']['project']['values']   = $this->marketresearch->getPairs();
        $searchConfig['params']['execution']['values'] = $this->execution->getPairs(0, 'stage');

        unset($searchConfig['fields']['module']);
        unset($searchConfig['fields']['type']);
        unset($searchConfig['fields']['fromBug']);
        unset($searchConfig['fields']['closedReason']);
        unset($searchConfig['fields']['closedBy']);
        unset($searchConfig['fields']['canceledBy']);
        unset($searchConfig['fields']['closedDate']);
        unset($searchConfig['fields']['canceledDate']);
        unset($searchConfig['params']['status']['values']['cancel']);
        unset($searchConfig['params']['status']['values']['closed']);

        $this->loadModel('search')->setSearchParams($searchConfig);

        return $searchConfig;
    }

    /**
     * 构建用户需求搜索表单。
     * Build Requirement search form.
     *
     * @param  int    $queryID
     * @param  string $actionURL
     * @access public
     * @return void
     */
    public function buildRequirementSearchForm($queryID, $actionURL)
    {
        $products = $this->dao->select('id,name')->from(TABLE_PRODUCT)
            ->where('deleted')->eq(0)
            ->beginIF(!$this->app->user->admin)->andWhere('id')->in($this->app->user->view->products)->fi()
            ->orderBy('order_asc')
            ->fetchPairs();

        $productIdList = array_keys($products);
        $this->app->loadConfig('product');
        $this->config->product->search['params']['roadmap']['values'] = $this->loadModel('roadmap')->getPairsForStory($productIdList, '', 'withMainRoadmap');
    }

    /**
     * 构建需求池需求搜索表单。
     * Build search form for demand page of work.
     *
     * @param  int    $queryID
     * @param  string $actionURL
     * @access public
     * @return bool
     */
    public function buildDemandSearchForm($queryID, $actionURL)
    {
        $this->loadModel('demand');
        $this->app->loadLang('roadmap');

        $rawMethod = $this->app->rawMethod;
        $this->config->demand->search['module']    = $rawMethod . 'Demand';
        $this->config->demand->search['actionURL'] = $actionURL;
        $this->config->demand->search['queryID']   = $queryID;
        $this->config->demand->search['params']['product']['values'] = arrayUnion(array('' => ''), $this->loadModel('product')->getPairs(), array('null' => $this->lang->roadmap->future));

        $this->config->demand->search['params']['pool']['values'] = arrayUnion(array('' => ''), $this->loadModel('demandpool')->getPairs());

        $this->loadModel('search')->setSearchParams($this->config->demand->search);

        return true;
    }

    /**
     * 通过搜索获取需求池需求。
     * Get demands by search.
     *
     * @param  string $account
     * @param  int    $queryID
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getDemandsBySearch($account, $queryID = 0, $orderBy = 'id_desc', $pager = null)
    {
        $moduleName = $this->app->rawMethod == 'work' ? 'workDemand' : 'contributeDemand';
        $queryName  = $moduleName . 'Query';
        $formName   = $moduleName . 'Form';

        if($queryID)
        {
            $query = $this->loadModel('search')->getQuery($queryID);
            if($query)
            {
                $this->session->set($queryName, $query->sql);
                $this->session->set($formName, $query->form);
            }
            else
            {
                $this->session->set($queryName, ' 1 = 1');
            }
        }
        else
        {
            if($this->session->{$queryName} == false) $this->session->set($queryName, ' 1 = 1');
        }

        $myDemandQuery = $this->session->{$queryName};
        $myDemandQuery = preg_replace('/`(\w+)`/', 't1.`$1`', $myDemandQuery);

        $currentMethod = $this->app->rawMethod;
        if($currentMethod == 'contribute')
        {
            $assignedByMe = $this->getDemandAssignedByMe($this->app->user->account, null, 'id_desc');
            if($assignedByMe) $assignedByMe = array_keys($assignedByMe);

            $demands = $this->dao->select("distinct t1.*, IF(t1.`pri` = 0, {$this->config->maxPriValue}, t1.`pri`) as priOrder, t2.name as poolName")->from(TABLE_DEMAND)->alias('t1')
               ->leftJoin(TABLE_DEMANDPOOL)->alias('t2')->on('t1.pool = t2.id')
               ->leftJoin(TABLE_DEMANDREVIEW)->alias('t3')->on('t1.id = t3.demand')
               ->where($myDemandQuery)
               ->andWhere('t1.createdBy',1)->eq($account)
               ->orWhere('t1.closedBy')->eq($account)
               ->orWhere("CONCAT(',', t1.reviewedBy, ',')")->like("%,$account,%")
               ->orWhere('t1.id')->in($assignedByMe)
               ->markRight(1)
               ->andWhere('t1.deleted')->eq(0)
               ->andWhere('t2.deleted')->eq(0)
               ->orderBy($orderBy)
               ->page($pager, 't1.id')
               ->fetchAll('id');
        }
        else
        {
            $demands = $this->dao->select("distinct t1.*, IF(t1.`pri` = 0, {$this->config->maxPriValue}, t1.`pri`) as priOrder, t2.name as poolName")->from(TABLE_DEMAND)->alias('t1')
               ->leftJoin(TABLE_DEMANDPOOL)->alias('t2')->on('t1.pool = t2.id')
               ->leftJoin(TABLE_DEMANDREVIEW)->alias('t3')->on('t1.id = t3.demand')
               ->where($myDemandQuery)
               ->andWhere('t1.assignedTo')->eq($account)
               ->andWhere('t1.deleted')->eq(0)
               ->andWhere('t2.deleted')->eq(0)
               ->orderBy($orderBy)
               ->page($pager, 't1.id')
               ->fetchAll('id');
        }
        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'demand', false);

        return $this->loadModel('demand')->mergeReviewer($demands);
    }

    /**
     * 获取由我指派的对象。
     * Get assigned by me objects.
     *
     * @param  string $account
     * @param object|null $pager
     * @param  string $orderBy
     * @access public
     * @return array
     */
    public function getDemandAssignedByMe($account, $pager = null, $orderBy = 'id_desc')
    {
        $objectIdList = $this->dao->select('objectID')->from(TABLE_ACTION)
            ->where('actor')->eq($account)
            ->andWhere('objectType')->eq('demand')
            ->andWhere('action')->eq('assigned')
            ->fetchPairs('objectID');
        if(empty($objectIdList)) return array();

        return $this->dao->select("distinct t1.*, IF(t1.`pri` = 0, {$this->config->maxPriValue}, t1.`pri`) as priOrder, t2.name as poolName")->from(TABLE_DEMAND)->alias('t1')
            ->leftJoin(TABLE_DEMANDPOOL)->alias('t2')->on('t1.pool = t2.id')
            ->leftJoin(TABLE_DEMANDREVIEW)->alias('t3')->on('t1.id = t3.demand')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t1.id')->in($objectIdList)
            ->orderBy($orderBy)
            ->page($pager, 't1.id')
            ->fetchAll('id');
    }
}
