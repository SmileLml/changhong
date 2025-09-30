<?php
helper::importControl('ai');
class myAI extends ai
{
    /**
     * Test connection with model configuration.
     *
     * @param  int     $modelID
     * @access public
     * @return void
     */
    public function modelTestConnection($modelID = 0)
    {
        $result = false;

        if(strtolower($this->server->request_method) == 'post')
        {
            /* Test model connection from form if method is POST. */
            $modelConfig = fixer::input('post')->get();

            $currentVendor = empty($modelConfig->vendor) ? key($this->lang->ai->models->vendorList->{empty($modelConfig->type) ? key($this->lang->ai->models->typeList) : $modelConfig->type}) : $modelConfig->vendor;
            $vendorRequiredFields = $this->config->ai->vendorList[$currentVendor]['credentials'];

            $errors = array();
            if(empty($modelConfig->type)) $errors[] = sprintf($this->lang->ai->validate->noEmpty, $this->lang->ai->models->type);
            foreach($vendorRequiredFields as $field)
            {
                if(empty($modelConfig->$field)) $errors[] = sprintf($this->lang->ai->validate->noEmpty, $this->lang->ai->models->$field);
            }
            if(!empty($modelConfig->proxyType) && empty($modelConfig->proxyAddr))
            {
                $errors[] = sprintf($this->lang->ai->validate->noEmpty, $this->lang->ai->models->proxyAddr);
            }
            if(!empty($errors)) return $this->send(array('result' => 'fail', 'message' => implode(' ', $errors)));

            $this->ai->setModelConfig($modelConfig);

            if($this->config->ai->models[$modelConfig->type] == 'ernie' || $currentVendor == 'azure' || $modelConfig->type == 'openai-gpt4' || $modelConfig->vendor == 'openaiCompatible')
            {
                // 本地gpt5国内转发接口不支持 max_tokens 参数，故再次去除，客户方若支持可去除
                $options = array();
                if($modelConfig->type != 'openai-gpt5mini') $options = array('maxTokens' => 1);

                $messages = array((object)array('role' => 'user', 'content' => 'test'));
                $result = $this->ai->converse(null, $messages, $options);
            }
            else
            {
                $result = $this->ai->complete(null, 'test', 1); // Test completing 'test' with length of 1.
            }
        }
        else
        {
            /* Test model with id if not POST. */
            $result = $this->ai->testModelConnection($modelID);
        }

        if($result === false)
        {
            return $this->send(array('result' => 'fail', 'message' => empty($this->ai->errors) ? $this->lang->ai->models->testConnectionResult->fail : sprintf($this->lang->ai->models->testConnectionResult->failFormat, implode(', ', $this->ai->errors))));
        }

        return $this->send(array('result' => 'success', 'message' => $this->lang->ai->models->testConnectionResult->success));
    }
}