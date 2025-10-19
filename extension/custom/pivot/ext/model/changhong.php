<?php

public function getAiScoreBug($begin = 0, $end = 0, $dept = 0, $user = '', $title = '', $pager = null)
{
    $workflowFields = $this->loadModel('workflowfield')->getFieldPairs('bug', 'custom', false, 'order', array('aiScore'), array('file'));
    $fields = $this->dao->select('DISTINCT field')->from(TABLE_AISCORE_RESULT)
        ->where('objectType')->eq('bug')
        ->andwhere('field')->ne('')
        ->fetchPairs('field');
    $fields = array_filter($fields, function($field) use ($workflowFields)
    {
        if(isset($this->lang->bug->$field)) return true;
        if(isset($workflowFields[$field]))
        {
            $this->lang->bug->$field = $workflowFields[$field];
            return true;
        }

        return false;
    });
    $deptUsers = $dept ? $this->loadModel('dept')->getDeptUserPairs($dept) : array();
    $scoreRecords = $this->dao->select('t1.*, t2.title, t2.project')->from(TABLE_AISCORE_RESULT)->alias('t1')
        ->leftJoin(TABLE_BUG)->alias('t2')->on('t1.objectID = t2.id')
        ->where('t1.objectType')->eq('bug')
        ->andWhere("t1.field")->ne('')
        ->andWhere("t1.createDate")->ge($begin)
        ->andWhere("t1.createDate")->lt($end)
        ->beginIF($title)->andWhere('t2.title')->like("%$title%")->fi()
        ->beginIF($user)->andWhere('t1.createBy')->eq($user)->fi()
        ->beginIF($deptUsers)->andWhere('t1.createBy')->in(array_keys($deptUsers))->fi()
        ->groupBy('t1.objectID')
        ->orderBy('t1.objectID desc, t1.times desc')
        ->page($pager, 't1.objectID')
        ->fetchGroup('objectID');

    if(empty($scoreRecords)) return array(array(), $fields);
    $bugScores = array();
    $linkUsers = array();
    foreach($scoreRecords as $bugID => $records)
    {
        $bugData = new stdclass();
        $bugData->objectID    = $bugID;
        $bugData->times       = 0;
        $bugData->createBy    = '';
        $bugData->createDate  = '';
        $bugData->remarkTimes = 0;
        $bugData->remarkScore = 0;
        foreach($records as $score)
        {
            if($score->times > $bugData->times)
            {
                $bugData->createBy   = $score->createBy;
                $bugData->createDate = $score->createDate;
                $bugData->times      = $score->times;
            }

            $fieldName = $score->field;
            if(!isset($bugData->$fieldName) || $score->times > $bugData->$fieldName->times)
            {
                $bugData->$fieldName = new stdclass();
                $bugData->$fieldName->score = $score->score;
                $bugData->$fieldName->times = $score->times;
            }
            if($fieldName == 'remark')
            {
                $bugData->remarkTimes++;
                $bugData->remarkScore += floatval($score->score);
            }
        }
        $bugData->remarkScore = $bugData->remarkTimes ? round($bugData->remarkScore / $bugData->remarkTimes, 2) : 0;
        foreach($bugData as $key => $value)
        {
            if(is_object($value) && property_exists($value, 'score')) $bugData->$key = $value->score;
        }
        if(!in_array($bugData->createBy, $linkUsers)) $linkUsers[] = $bugData->createBy;
        $bugScores[$bugID] = $bugData;
    }

    $linkProjects = $this->dao->select('t1.id,t1.title,t2.name as projectName')->from(TABLE_BUG)->alias('t1')
        ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')
        ->where('t1.id')->in(array_keys($bugScores))
        ->fetchAll('id');

    $linkUsers = $this->dao->select('id,account,realname,dept')->from(TABLE_USER)
        ->where('account')
        ->in($linkUsers)
        ->fetchAll('account');
    foreach($bugScores as $bugScore)
    {
        $bugScore->projectName = isset($linkProjects[$bugScore->objectID]) && !empty($linkProjects[$bugScore->objectID]->projectName) ? $linkProjects[$bugScore->objectID]->projectName : '-';
        $bugScore->title       = isset($linkProjects[$bugScore->objectID]) && !empty($linkProjects[$bugScore->objectID]->title) ? $linkProjects[$bugScore->objectID]->title : '-';
        $bugScore->accountDept = $linkUsers[$bugScore->createBy]->dept;
        $bugScore->accountName = $linkUsers[$bugScore->createBy]->realname;
    }
    return array($bugScores, $fields);
}
