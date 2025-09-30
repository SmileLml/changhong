<?php
/**
 * The get bug by list entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chenxuan Song
 * @package     entries
 * @version     1
 * @link        http://www.zentao.net
 **/
class bugsByListEntry extends entry
{
    /**
     * POST method.
     *
     * @access public
     * @return string
     */
    public function post()
    {
        $bugIDList = $this->request('bugIDList', array());

        if(empty($bugIDList))  return $this->sendError(400, 'Need bugIDList.');

        $results = array();
        $bugs    = $this->loadModel('bug')->getByList($bugIDList);
        foreach($bugs as $bug)
        {
            $results[] = $this->format($bug, 'openedBy:user,openedDate:time,activatedDate:time,deadline:date,resolvedBy:user,resolvedDate:time,assignedTo:user,assignedDate:time,reviewedBy:user,reviewedDate:time,lastEditedBy:user,lastEditedDate:time,closedBy:user,closedDate:time,deleted:bool,mailto:userList');
        }

        return $this->send(200, $results);
    }

}
