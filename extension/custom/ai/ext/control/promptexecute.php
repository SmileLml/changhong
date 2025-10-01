<?php
helper::importControl('ai');
class myAI extends ai
{
        /**
     * Execute prompt on obejct, and redirect to target form page.
     *
     * @param  int    $promptId
     * @param  int    $objectId
     * @access public
     * @return void
     */
    public function promptExecute($promptId, $objectId)
    {
        if(!$this->ai->hasModelsAvailable()) return $this->send(array('result' => 'fail', 'message' => $this->lang->ai->models->noModelError, 'locate' => $this->inlink('models') . '#app=admin'));

        $prompt = $this->ai->getPromptByID($promptId);
        if(empty($prompt) || !$this->ai->isExecutable($prompt)) return $this->send(array('result' => 'fail', 'message' => sprintf($this->lang->ai->execute->failFormat, $this->lang->ai->execute->failReasons['noPrompt'])));

        $object = $this->ai->getObjectForPromptById($prompt, $objectId);
        if(empty($object)) return $this->send(array('result' => 'fail', 'message' => sprintf($this->lang->ai->execute->failFormat, $this->lang->ai->execute->failReasons['noObjectData'])));

        list($objectData, $rawObject) = $object;
        if(!empty($prompt->targetForm))
        {
            list($location, $stop) = $this->ai->getTargetFormLocation($prompt, $rawObject);
            if(empty($location)) return $this->send(array('result' => 'fail', 'message' => sprintf($this->lang->ai->execute->failFormat, $this->lang->ai->execute->failReasons['noTargetForm'])));
            if(!empty($stop))    return header("location: $location", true, 302);
        }

        /* Execute prompt and catch exceptions. */
        try
        {
            $response = $this->ai->executePrompt($prompt, $object);
        }
        catch (AIResponseException $e)
        {
            $output = array('result' => 'fail', 'message' => sprintf($this->lang->ai->execute->failFormat, $e->getMessage()));

            /* Audition shall quit on such exception. */
            if(isset($_SESSION['auditPrompt']) && time() - $_SESSION['auditPrompt']['time'] < 10 * 60)
            {
                $output['locate'] = $this->inlink('promptAudit', "promptID=$promptId&objectId=$objectId&exit=true");
            }
            return $this->send($output);
        }

        if(is_int($response)) return $this->send(array('result' => 'fail', 'message' => sprintf($this->lang->ai->execute->failFormat, $this->lang->ai->execute->executeErrors["$response"]) . (empty($this->ai->errors) ? '' : implode(', ', $this->ai->errors))));
        if(empty($response))  return $this->send(array('result' => 'fail', 'message' => sprintf($this->lang->ai->execute->failFormat, $this->lang->ai->execute->failReasons['noResponse'])));

        $this->ai->setInjectData($prompt->targetForm, $response);

        $_SESSION['aiPrompt']['prompt']   = $prompt;
        $_SESSION['aiPrompt']['objectId'] = $objectId;

        if($prompt->status == 'draft') $_SESSION['auditPrompt']['time'] = time();

        $this->view->formLocation   = $location;
        $this->view->promptViewLink = $this->inlink('promptview', "promptID=$promptId");
        $this->display();
    }
}
