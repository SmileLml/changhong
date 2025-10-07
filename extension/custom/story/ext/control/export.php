<?php
helper::importControl('story');
class myStory extends story
{
    /**
     * 导出需求数据。
     * Get the data of the stories to export.
     *
     * @param  int    $productID
     * @param  string $orderBy
     * @param  int    $executionID
     * @param  string $browseType
     * @param  string $storyType   requirement|story
     * @access public
     * @return void
     */
    public function export($productID, $orderBy, $executionID = 0, $browseType = '', $storyType = 'story')
    {
        /* format the fields of every story in order to export data. */
        if($_POST)
        {
            $this->loadModel('transfer');
            $postData = form::data($this->config->transfer->form->export)->get();

            $this->session->set("{$storyType}TransferParams", array('productID' => $productID, 'executionID' => $executionID));

            /* 给评审过的人员添加下拉选择，方便再次导入时转换成待评审人员。*/
            /* Add a drop-down selection to the reviewer to facilitate the conversion to the reviewer during import. */
            $this->config->story->dtable->fieldList['reviewedBy']['control']    = 'multiple';
            $this->config->story->dtable->fieldList['reviewedBy']['dataSource'] = array('module' => 'story', 'method' => 'getProductReviewers', 'params' => array('productID' => (int)$productID));

            if($executionID) $this->lang->story->title = $this->lang->story->name;

            /* Create field lists. */
            if(!$productID or $browseType == 'bysearch')
            {
                $this->config->story->dtable->fieldList['branch']['dataSource']           = array('module' => 'branch', 'method' => 'getAllPairs');
                $this->config->story->dtable->fieldList['module']['dataSource']['method'] = 'getAllModulePairs';
                $this->config->story->dtable->fieldList['module']['dataSource']['params'] = 'story';

                $this->config->story->dtable->fieldList['project']['dataSource'] = array('module' => 'project', 'method' => 'getPairsByIdList', 'params' => $executionID);
                $this->config->story->dtable->fieldList['execution']['dataSource'] = array('module' => 'execution', 'method' => 'getPairs', 'params' => $executionID);

                $products      = $this->loadModel('product')->getPairs('all', 0, '', 'all');
                $productIdList = array_keys($products);

                $this->config->story->dtable->fieldList['plan']['dataSource'] = array('module' => 'productplan', 'method' => 'getPairs', 'params' => array($productIdList));
            }

            $this->fetch('transfer', 'export', "model=$storyType");
        }

        $this->story->replaceURLang($storyType);

        $project  = null;
        $hasBranch = false;
        if($executionID)
        {
            $execution = $this->loadModel('execution')->getByID($executionID);
            $fileName  = $execution->name . $this->lang->dash . $this->lang->common->story;
            $project   = $execution;
            if($execution->type == 'execution') $project = $this->project->getById($execution->project);
            $this->lang->story->title = $this->lang->story->name;

            $products  = $this->loadModel('product')->getProducts($executionID);
            foreach($products as $product)
            {
                if($product->type != 'normal') $hasBranch = true;
            }
        }
        else
        {
            $productName = $this->lang->product->all;
            if($productID)
            {
                $product     = $this->product->getById($productID);
                $productName = $product->name;

                if($product->shadow) $project = $this->project->getByShadowProduct($productID);
                if($product->type != 'normal') $hasBranch = true;
            }
            if(isset($this->lang->product->featureBar['browse'][$browseType]))
            {
                $browseType = $this->lang->product->featureBar['browse'][$browseType];
            }
            else
            {
                $browseType = isset($this->lang->product->moreSelects[$browseType]) ? $this->lang->product->moreSelects[$browseType] : '';
            }

            $fileName = $productName . $this->lang->dash . $browseType . $this->lang->common->story;
        }

        /* Unset branch field.  */
        if(!$hasBranch) $this->config->story->exportFields = str_replace(', branch', '', $this->config->story->exportFields);

        /* If or vision, unset plan field. */
        if($this->config->vision == 'or') $this->config->story->exportFields = str_replace(', plan,', ',', $this->config->story->exportFields);

        /* Unset product field when in single project.  */
        if(isset($project->hasProduct) && !$project->hasProduct)
        {
            $filterFields = array(', product,', ', branch,');
            if($project->model != 'scrum') $filterFields[] = ', plan,';
            $this->config->story->exportFields = str_replace($filterFields, ',', $this->config->story->exportFields);
        }

        /* Append workflow field. */
        if($this->config->edition != 'open')
        {
            $exportFlowFields = $this->loadModel('workflowfield')->getExportFields($storyType);
            if(!$this->loadModel('ai')->checkPromptByModule($storyType)) unset($exportFlowFields['aiScore']);
            foreach($exportFlowFields as $field => $name)
            {
                $this->config->story->exportFields .= ",{$field}";
                $this->lang->story->{$field} = $name;
            }
        }

        $this->view->fileName        = $fileName;
        $this->view->allExportFields = $this->config->story->exportFields;
        $this->view->customExport    = true;
        $this->view->storyType       = $storyType;
        $this->display();
    }
}
