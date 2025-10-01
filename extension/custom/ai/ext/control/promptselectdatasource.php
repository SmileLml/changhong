<?php
helper::importControl('ai');
class myAI extends ai
{
    /**
     * Set data source of prompt, prompt editing step 3.
     *
     * @param  int    $promptID
     * @access public
     * @return void
     */
    public function promptSelectDataSource($promptID)
    {
        $prompt = $this->ai->getPromptByID($promptID);

        if($_POST)
        {
            $data = fixer::input('post')->get();

            $originalPrompt = clone $prompt;

            $prompt->module = $data->datagroup;
            $prompt->source = ",$data->datasource,";
            $prompt->weight = ",$data->datasourceweight,";

            $this->ai->updatePrompt($prompt, $originalPrompt);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if(!empty($data->jumpToNext)) return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->inlink('promptSetPurpose', "promptID=$promptID") . '#app=admin'));

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->inlink('promptSelectDataSource', "promptID=$promptID") . '#app=admin'));
        }
        $scoreWeights             = $this->ai->getScoreWeights($promptID);
        $prompt->datasourceweight = 0.00;
        foreach($scoreWeights as $sourceWeight)
        {
            $prompt->datasourceweight += (float) $sourceWeight->weight;
        }
        $prompt->datasourceweight     = round($prompt->datasourceweight, 2);
        $this->view->activeDataSource = empty($prompt->module) ? current(array_keys($this->config->ai->dataSource)) : $prompt->module;
        $this->view->dataSource       = $this->ai->getDataSource();
        $this->view->prompt           = $prompt;
        $this->view->scoreWeights     = $scoreWeights;
        $this->view->promptID         = $promptID;
        $this->view->lastActiveStep   = $this->ai->getLastActiveStep($prompt);
        $this->view->title            = "{$this->lang->ai->prompts->common}#{$prompt->id} $prompt->name {$this->lang->hyphen} " . $this->lang->ai->prompts->selectDataSource . " {$this->lang->hyphen} " . $this->lang->ai->prompts->common;
        $this->display();
    }
}
