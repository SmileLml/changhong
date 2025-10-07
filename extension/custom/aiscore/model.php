<?php
class aiscoreModel extends model
{
    /**
     * Get rules list.
     *
     * @param  string $objectType
     * @access public
     * @return array
     */
    public function getRulesList($objectType)
    {
        return $this->dao->select('*')->from(TABLE_AISCORE_RULES)
            ->where('objectType')->eq($objectType)
            ->orderBy('id_desc')
            ->fetchAll('field');
    }

    /**
     * Get weight pairs.
     *
     * @param  int    $promptID
     * @access public
     * @return array
     */
    public function getWeightPairs($promptID)
    {
        return $this->dao->select('field,weight')->from(TABLE_AISCORE_WEIGHT)
            ->where('promptID')->eq($promptID)
            ->orderBy('id_desc')
            ->fetchPairs();
    }

    /**
     * Get score times.
     *
     * @param  string $objectType
     * @param  int    $objectID
     * @access public
     * @return int
     */
    public function getScoreTimes($objectType, $objectID)
    {
        $times = $this->dao->select('max(times) as times')->from(TABLE_AISCORE_RESULT)
            ->where('objectType')->eq($objectType)
            ->andWhere('objectID')->eq($objectID)
            ->fetch('times');

        return empty($times) ? 0 : $times;
    }

    /**
     * Save sorce result.
     *
     * @param  int    $promptID
     * @param  string $scores
     * @param  string $objectType
     * @param  int    $objectID
     * @param  string $action
     * @access public
     * @return void
     */
    public function saveAIScoreResult($promptID, $scores, $objectType, $objectID, $action)
    {
        $totalScore = 0;

        $times            = $this->getScoreTimes($objectType, $objectID);
        $fieldWeightPairs = $this->getWeightPairs($promptID);
        $scoreList        = json_decode($scores, true);

        $fieldScore             = new stdclass();
        $fieldScore->objectType = $objectType;
        $fieldScore->objectID   = $objectID;
        $fieldScore->action     = strtolower($action);
        $fieldScore->times      = $times + 1;
        $fieldScore->createBy   = $this->app->user->account;
        $fieldScore->createDate = helper::now();

        foreach($scoreList as $field => $score)
        {
            $fieldScore->field = $field;
            $fieldScore->score = $score;

            $this->dao->insert(TABLE_AISCORE_RESULT)->data($fieldScore)->exec();

            if(isset($fieldWeightPairs[$field])) $totalScore += $fieldWeightPairs[$field] * 0.01 * $score;
        }

        $fieldScore->field = '';
        $fieldScore->score = $totalScore;
        $this->dao->insert(TABLE_AISCORE_RESULT)->data($fieldScore)->exec();

        $table = $this->loadModel('workflow')->getTableByModule($objectType);
        $this->dao->update($table)->set('aiScore')->eq($totalScore)->where('id')->eq($objectID)->exec();
    }

    /**
     * Get AI score results by object.
     *
     * @param  string $objectType
     * @param  int    $objectID
     * @param  array  $fields
     * @access public
     * @return array
     */
    public function getResultByObject($objectType, $objectID, $fields)
    {
        $scoreRecords = $this->dao->select('*')->from(TABLE_AISCORE_RESULT)
            ->where('objectType')->eq($objectType)
            ->andWhere('objectID')->eq($objectID)
            ->fetchGroup('times');

        if(empty($scoreRecords)) return array();

        $results = array();

        foreach($scoreRecords as $times => $records) 
        {
            $baseRecord = $records[0];

            $result = new stdclass();
            $result->id              = $times;
            $result->action          = $baseRecord->action;
            $result->aiRequestStatus = 'doing';
            $result->createDate      = $baseRecord->createDate;
            $result->summary         = 0;
            $result->details         = array();

            foreach($records as $record) 
            {
                if(empty($record->field)) 
                {
                    $result->summary = $record->score;
                } 
                else 
                {
                    $result->details[$record->field] = $record;
                }
            }

            foreach($fields as $field) 
            {
                if(!isset($result->details[$field])) 
                {
                    $result->$field = '-';
                } 
                else if(empty($result->details[$field]->score) && $result->details[$field]->score !== '0') 
                {
                    $result->$field = '';
                } 
                else 
                {
                    $result->$field = $result->details[$field]->score;
                }
            }

            $results[$times] = $result;
        }

        return $results;
    }

    public function updateResultTableConfig($objectType, $fields)
    {
        $this->config->aiscore->dtable->fieldList['action']['map'] = $this->lang->ai->triggerAction[$objectType];
        $colIndex = count($this->config->aiscore->dtable->fieldList);
        foreach($fields as $field)
        {
            $this->config->aiscore->dtable->fieldList[$field]['title']    = $this->lang->aiscore->$field;
            $this->config->aiscore->dtable->fieldList[$field]['type']     = 'text';
            $this->config->aiscore->dtable->fieldList[$field]['align']    = 'center';
            $this->config->aiscore->dtable->fieldList[$field]['sortType'] = false;
            $this->config->aiscore->dtable->fieldList[$field]['group']    = ++$colIndex;
        }
    }
}
