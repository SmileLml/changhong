<?php
/**
 * 按执行统计的截止执行开始当天的研发需求数
 * Count of story in execution when starting.
 *
 * 范围：execution
 * 对象：story
 * 目的：scale
 * 度量名称：按执行统计的截止执行开始当天的研发需求数
 * 单位：个
 * 描述：按执行统计的截止执行开始当天的研发需求数表示执行开始当天已关联进执行的研发需求的数量。该度量项反映了本期执行计划完成的需求数量，可以用于评估执行团队的工作负载。
 * 定义：截止到执行开始当天的23:59分的研发需求个数求和，过滤已删除的研发需求，过滤已删除的执行，过滤已删除的项目，过滤已删除的产品。
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    songchenxuan <songchenxuan@chandao.com>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_story_in_execution_when_starting extends baseCalc
{
    public $dataset = 'getExecutionDevStories';

    public $fieldList = array('t1.id as execution', 't1.multiple', "if(t1.multiple = '1', t1.realBegan, t5.realBegan) as realBegan", 't3.story', 't6.action', 't6.id as actionID', 't6.date as actionDate');

    public $result = array();

    public $initRecord = false;

    public $storyInfo = array();
    /* e.g $storyInfo[3] = array('link' => 1, 'unlink' => 1, execution => 3); */

    public function calculate($row)
    {
        // 关联、取消关联的时间是否小于等于项目的开始时间
        $actionDate = !helper::isZeroDate($row->actionDate) ? substr($row->actionDate, 0, 10) : null;
        $realBegan  = !helper::isZeroDate($row->realBegan)  ? $row->realBegan : null;

        $condition1 = ($actionDate && $realBegan && $actionDate <= $realBegan);
        if($condition1)
        {
            if(!isset($this->storyInfo[$row->execution])) $this->storyInfo[$row->execution] = array();
            if(!isset($this->storyInfo[$row->execution][$row->story])) $this->storyInfo[$row->execution][$row->story] = array('link' => 0, 'unlink' => 0);
            if($row->action == 'linked2execution'      || ($row->multiple == '0' && $row->action == 'linked2project'))      $this->storyInfo[$row->execution][$row->story]['link']   += 1;
            if($row->action == 'unlinkedfromexecution' || ($row->multiple == '0' && $row->action == 'unlinkedfromproject')) $this->storyInfo[$row->execution][$row->story]['unlink'] += 1;
        }
    }

    public function getResult($options = array())
    {
        foreach($this->storyInfo as $execution => $storyInfo)
        {
            foreach($storyInfo as $story)
            {
                $link   = $story['link'];
                $unlink = $story['unlink'];

                if($link - $unlink <= 0) continue;

                if(!isset($this->result[$execution])) $this->result[$execution] = 0;
                $this->result[$execution] += 1;
            }
        }

        $executions = $this->getExecutions();
        $beginExecutions = array_filter($executions, function($execution) { return !empty($execution->realBegan); });
        foreach($beginExecutions as $executionID => $executionInfo)
        {
            if(!isset($this->result[$executionID])) $this->result[$executionID] = 0;
        }

        $records = $this->getRecords(array('execution', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
