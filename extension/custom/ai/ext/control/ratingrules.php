<?php
helper::importControl('ai');
class myAI extends ai
{
    public function ratingRules($objectType = 'story', $storyType = '')
    {
        $fields = $this->config->ai->dataSource[$objectType];
        $customedFields = $this->loadModel('workflowfield')->getCustomedFields($objectType , 100);
        foreach($customedFields as $groupList)
        {
            foreach($groupList as $key => $fieldInfo)
            {
                $customFields[$key]['field'] = $fieldInfo->field;
                $customFields[$key]['name'] = $fieldInfo->name;
            }
        }
        if(!empty($_POST))
        {
            $data = fixer::input('post')->get();
            $this->ai->ratingRules($objectType , $data);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            return $this->send(array('result' => 'success','message' => $this->lang->saveSuccess));
        }

        $this->app->loadLang($objectType);
        $rules  = $this->ai->getRulesByObjectType($objectType);

        $this->view->title        = $this->lang->ai->ratingRules->common;
        $this->view->objectType   = $objectType;
        $this->view->fields       = $fields;
        $this->view->rules        = $rules;
        $this->view->customFields = $customFields;
        $this->display('ai', 'ratingrules');

    }
}
