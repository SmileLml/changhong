<?php

/**
 * Get products by account.
 *
 * @param  int        $programID
 * @param  string     $status
 * @param  bool       $isAdmin
 * @param  array      $projectIDList
 * @param  string     $account
 * @access public
 * @return array
 */
public function getListByAccount($programID = 0, $browseType = 'all', $isAdmin = false, $projectIDList = array(), $account = '')
{
    $projectList = $this->dao->select('distinct t1.*')->from(TABLE_PROJECT)->alias('t1')
        ->leftJoin(TABLE_TEAM)->alias('t2')->on('t1.id=t2.root')
        ->leftJoin(TABLE_STAKEHOLDER)->alias('t3')->on('t1.id=t3.objectID')
        ->where('t1.deleted')->eq('0')
        ->andWhere('t1.vision')->eq($this->config->vision)
        ->andWhere('t1.type')->eq('project')
        ->beginIF(!in_array($browseType, array('all', 'undone', 'bysearch', 'review', 'unclosed'), true))->andWhere('t1.status')->eq($browseType)->fi()
        ->beginIF($browseType == 'undone' or $browseType == 'unclosed')->andWhere('t1.status')->in('wait,doing')->fi()
        ->beginIF(!$isAdmin)->andWhere('t1.id')->in($projectIDList)->fi()
        ->orderBy('id_desc')
        ->fetchAll('id');

    return $projectList;
}
