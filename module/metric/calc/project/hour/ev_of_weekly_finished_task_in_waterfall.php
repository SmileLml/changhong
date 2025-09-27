<?php
/**
 * 按瀑布项目统计的截止本周已完成任务工作的预计工时(EV)。
 * Ev of weekly finished task in waterfall.
 *
 * 范围：project
 * 对象：task
 * 目的：hour
 * 度量名称：按瀑布项目统计的截止本周已完成任务工作的预计工时(EV)
 * 单位：小时
 * 描述：按瀑布项目统计的截止本周已完成任务工作的预计工时指的是在瀑布项目管理方法中，已经完成的任务的预计工时。这个度量项用来评估项目进展与实际完成情况的一致性。EV的值越高，代表项目团队在按计划完成任务的工作量方面表现得越好。
 * 定义：复用： 按项目统计的任务进度、按项目统计的任务预计工时数，公式： 按项目统计的已完成任务工作的预计工时(EV)=按项目统计的任务预计工时数*按项目统计的任务进度；要求项目为瀑布项目，过滤父任务，过滤消耗工时为0的任务，过滤已删除的任务，过滤已取消的任务，过滤已删除执行下的任务，过滤已删除的项目。
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class ev_of_weekly_finished_task_in_waterfall extends baseCalc
{
    public $dataset = 'getWaterfallTasks';

    public $fieldList = array('t1.project', 't1.estimate', 't1.consumed', 't1.left', 't1.status', 't1.closedReason');

    public $result = array();

    public function calculate($row)
    {
        $project      = $row->project;
        $status       = $row->status;
        $closedReason = $row->closedReason;
        $estimate     = (float)$row->estimate;
        $consumed     = (float)$row->consumed;
        $left         = (float)$row->left;
        $total        = $consumed + $left;

        if($consumed == 0) return false;

        $ev = 0;
        if($status == 'done' || $closedReason == 'done')
        {
            $ev = $estimate;
        }
        else
        {
            $ev = $total == 0 ? 0 : round($consumed / $total * $estimate, 2);
        }

        if(!isset($this->result[$project])) $this->result[$project] = 0;
        $this->result[$project] += $ev;
    }

    public function getResult($options = array())
    {
        $result = $this->result;
        if(isset($options['year']) && isset($options['week']))
        {
            $year = $options['year'];
            $week = $options['week'];
        }
        else
        {
            $date = date('Y-m-d');
            $year = $this->getYear($date);
            $week = $this->getWeek($date);
        }

        foreach($result as $project => $ev)
        {
            $result[$project] = array($year => array($week => $ev));
        }

        $records = $this->getRecords(array('project', 'year', 'week', 'value'), $result);
        return $this->filterByOptions($records, $options);
    }
}
