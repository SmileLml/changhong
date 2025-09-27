<?php
namespace zin;

require_once dirname(__DIR__) . DS . 'dtable' . DS . 'v1.php';

class taskTeam extends wg
{
    /**
     * @var mixed[]
     */
    protected static $defineProps = array
    (
        'team'       => '?object',   // 团队信息。
        'users'      => '?array'     // 用户列表。
    );

    protected function getData()
    {
        $team = $this->prop('team');
        if(is_null($team))
        {
            $task = data('task');
            if(is_object($task) && isset($task->team)) $team = $task->team;
        }

        return $team;
    }

    protected function getCols()
    {
        global $lang;

        $cols = array();

        $cols['account']  = array('title' => $lang->task->team, 'type' => 'user', 'fixed' => 'left', 'sort' => true);
        $cols['estimate'] = array('title' => $lang->task->estimateAB, 'type' => 'number', 'flex' => 1, 'sort' => true);
        $cols['consumed'] = array('title' => $lang->task->consumedAB, 'type' => 'number', 'flex' => 1, 'sort' => true);
        $cols['left']     = array('title' => $lang->task->leftAB, 'type' => 'number', 'flex' => 1, 'sort' => true);
        $cols['status']   = array('title' => $lang->task->statusAB, 'type' => 'status', 'statusMap' => $this->prop('statusMap', $lang->task->statusList), 'flex' => 1, 'sort' => true);

        return $cols;
    }

    protected function build()
    {
        $users = $this->prop('users', data('users'));
        return new dtable
        (
            set::_className('task-team-table ring'),
            set::cols($this->getCols()),
            set::data($this->getData()),
            set::userMap($users),
            set::horzScrollbarPos('inside'),
            set::scrollbarSize(5)
        );
    }
}
