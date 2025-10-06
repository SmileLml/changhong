<?php
helper::importControl('productplan');

class myProductplan extends productplan
{
    /**
     * 编辑一个计划。
     * Edit a plan.
     *
     * @param  int    $planID
     * @access public
     * @return void
     */
    public function edit($planID)
    {
        parent::edit($planID);
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
