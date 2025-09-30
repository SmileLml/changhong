<?php
/**
 * The control file of requestlog module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2020 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yong Lei <leiyong@easycorp.ltd>
 * @package     requestlog
 * @version     $Id: control.php 5107 2020-09-09 09:46:12Z leiyong@easycorp.ltd $
 * @link        http://www.zentao.net
 */
class requestlog extends control
{
    /**
     * Browse the request logs with third-party interfaces.
     *
     * @param  string $browseType
     * @param  int    $param
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function browse($browseType = 'all', $param = 0, $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->loadModel('datatable');

        /* Load the paging tool class and initialize the paging object. */
        $this->app->loadClass('pager', true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        /* Build the search form. */
        $queryID   = ($browseType == 'bysearch') ? (int)$param : 0;
        $actionURL = $this->createLink('requestlog', 'browse', 'browseType=bysearch&queryID=myQueryID');
        $this->requestlog->buildSearchForm($actionURL, $queryID);

        /* Pass the fetched data to the page for display. */
        $this->view->title      = $this->lang->requestlog->log;
        $this->view->position[] = $this->lang->requestlog->log;

        $this->view->logList    = $this->requestlog->getLogList($browseType, $queryID, $orderBy, $pager);
        $this->view->pager      = $pager;
        $this->view->param      = $param;
        $this->view->orderBy    = $orderBy;
        $this->view->browseType = $browseType;

        $this->display();
    }

    /**
     * Get request parameter information.
     *
     * @param int $id
     * @access public
     * @return void
     */
    public function ajaxGetParams($id)
    {
        $this->view->params = $this->requestlog->getByID($id);
        $this->display();
    }

    /**
     * Get the response result.
     *
     * @param int $id
     * @access public
     * @return void
     */
    public function ajaxGetResponse($id)
    {
        $this->view->response = $this->requestlog->getByID($id);
        $this->display();
    }

    /**
     * 删除过期日志。
     * Delete logs older than save days.
     *
     * @access public
     * @return bool
     */
    public function delete($keep = '90')
    {
        $this->app->loadLang('ai');
        if(ctype_digit($keep)==false || $keep < '5') return $this->send(array('result' => 'fail', 'message' => $this->lang->requestlog->keepDays));
        $date = date(DT_DATE1, strtotime("-{$keep} days"));
        $this->dao->delete()->from(TABLE_REQUESTLOG)->where('requestTime')->lt($date)->andWhere('purpose')->eq($this->lang->ai->promptMenu->dropdownTitle)->exec();
        return !dao::isError();
    }

}
