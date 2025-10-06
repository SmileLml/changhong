<?php
helper::importControl('productplan');

class myProductplan extends productplan
{
    /**
     * 创建一个计划。
     * Create a plan.
     *
     * @param  int    $productID
     * @param  int    $branchID
     * @param  int    $parent
     * @access public
     * @return void
     */
    public function create($productID = 0, $branchID = 0, $parent = 0)
    {
        parent::create($productID, $branchID, $parent);
    }

    /**
     * 设置公共属性。
     * Common actions.
     *
     * @param  int    $productID
     * @param  int    $branch
     * @param  bool   $isFromDoc
     * @access public
     * @return void
     */
    public function commonAction($productID, $branch = 0, $isFromDoc = false)
    {
        parent::commonAction($productID, $branch, $isFromDoc);
        $this->view->aiWeightField = $this->appendAiWeightField();
    }
}
