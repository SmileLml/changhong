<?php
/**
 * The model file of requestlog module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2020 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yong Lei <leiyong@easycorp.ltd>
 * @package     requestlog
 * @version     $Id: model.php 5107 2020-09-09 09:46:12Z leiyong@easycorp.ltd $
 * @link        http://www.zentao.net
 */
class requestlogModel extends model
{
    /**
     * Print cell data.
     *
     * @param  object $col
     * @param  object $log
     * @param  string $mode
     *
     * @access public
     * @return void
     */
    public function printCell($col, $log, $mode = 'datatable')
    {
        if($col->show)
        {
            $id    = $col->id;
            $class = "c-$id";
            $title = '';

            switch($id)
            {
                case 'id':
                    $class .= ' cell-id';
                    break;
                case 'url':
                    $title  = "title='" . $log->url . "'";
                    $class .= ' text-ellipsis';
                    break;
                case 'actions':
                    $class .= ' text-center';
                    break;
            }

            echo "<td class='" . $class . "' $title>";
            switch($id)
            {
                case 'id':
                    printf('%03d', $log->id);
                    break;
                case 'url':
                    echo $log->url;
                    break;
                case 'objectType':
                    echo zget($this->lang->requestlog->objectTypeList, $log->objectType, '');
                    break;
                case 'purpose':
                    echo zget($this->lang->requestlog->purposeList, $log->purpose, '');
                    break;
                case 'requestType':
                    echo $log->requestType;
                    break;
                case 'status':
                    echo zget($this->lang->requestlog->statusList, $log->status, '');
                    break;
                case 'params':
                    $viewUrl = helper::createLink('requestlog', 'ajaxGetParams', 'id=' . $log->id);
                    echo html::commonButton($this->lang->requestlog->details, 'data-type="ajax" data-title="' . $this->lang->requestlog->params . '" data-remote="' . $viewUrl . '" data-toggle="modal"', 'btn btn-mini btn-primary triggerButton');
                    break;
                case 'response':
                    $viewUrl = helper::createLink('requestlog', 'ajaxGetResponse', 'id=' . $log->id);
                    echo html::commonButton($this->lang->requestlog->details, 'data-type="ajax" data-title="' . $this->lang->requestlog->response . '" data-remote="' . $viewUrl . '" data-toggle="modal"', 'btn btn-mini btn-primary triggerButton');
                    break;
                case 'requestDate':
                    echo $log->requestDate;
                    break;
                case 'actions':
                    echo $this->lang->noData;
                    break;
            }
            echo '</td>';
        }
    }

    /**
     * Configuration parameters required to handle paging.
     * 处理分页所需的配置参数。
     *
     * @param  string $actionURL
     * @param  int    $queryID
     * @access public
     * @return void
     */
    public function buildSearchForm($actionURL, $queryID)
    {
        $this->config->requestlog->search['actionURL'] = $actionURL;
        $this->config->requestlog->search['queryID']   = $queryID;

        $this->loadModel('search')->setSearchParams($this->config->requestlog->search);
    }

    /**
     * Obtain request log information by id.
     * 通过id获取请求日志信息。
     *
     * @param  int    $id
     * @access public
     * @return void
     */
    public function getByID($id)
    {
        return $this->dao->select('*')->from(TABLE_REQUESTLOG)->where('id')->eq($id)->fetch();
    }

    /**
     * Query request log list page data.
     * 查询请求日志列表页数据。
     *
     * @param  string $actionURL
     * @param  int    $queryID
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return void
     */
    public function getLogList($browseType = 'all', $queryID = 0, $orderBy = 'id_desc', $pager = null)
    {
        /* Get the query criteria from session. */
        /* 从session中获取查询条件。*/
        $requestlogQuery = '';
        if($browseType == 'bysearch')
        {
            $query = $queryID ? $this->loadModel('search')->getQuery($queryID) : '';
            if($query)
            {
                $this->session->set('requestlogQuery', $query->sql);
                $this->session->set('requestlogForm', $query->form);
            }
            if(!$this->session->requestlogQuery) $this->session->set('requestlogQuery', ' 1=1');
            $requestlogQuery = $this->session->requestlogQuery;
        }

        return $this->dao->select('*')->from(TABLE_REQUESTLOG)
            ->where('id')->gt(0)
            ->beginIF($browseType == 'bysearch')->andWhere($requestlogQuery)->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll();
    }

    /**
     * Save the request log.
     * 保存请求日志。
     *
     * @param  array  $responseData
     * @access public
     * @return int
     */
    public function saveRequestLog($responseData = array())
    {
        $log = new stdClass();
        $log->url          = isset($responseData['url'])          ? $responseData['url']          : '';
        $log->responseTime = isset($responseData['responseTime']) ? $responseData['responseTime'] : 0;
        $log->statusCode   = isset($responseData['statusCode'])   ? $responseData['statusCode']   : 0;
        $log->status       = isset($responseData['status'])       ? $responseData['status']       : '';
        $log->clientIP     = isset($responseData['clientIP'])     ? $responseData['clientIP']     : '';
        $log->requestUser  = isset($responseData['requestUser'])  ? $responseData['requestUser']  : '';
        $log->requestTime  = isset($responseData['requestTime'])  ? $responseData['requestTime']  : '0000-00-00 00:00:00';
        $log->params       = isset($responseData['params'])       ? $responseData['params']       : '';
        $log->response     = isset($responseData['response'])     ? $responseData['response']     : '';
        $log->purpose      = isset($responseData['purpose'])      ? $responseData['purpose']      : '';
        $log->requestType  = isset($responseData['requestType'])  ? $responseData['requestType']  : 'POST';

        $this->dao->insert(TABLE_REQUESTLOG)->data($log)->exec();
        return array('logData' => $log, 'logID' => $this->dao->lastInsertId());
    }

    /**
     * Responses and results of update request logs.
     * 更新请求日志的响应和结果。
     *
     * @param  int    $logID
     * @param  array  $result
     * @access public
     * @return void
     */
    public function updateRequestLog($logID = 0, $result = array())
    {
        if(!empty($logID))
        {
            $data = new stdClass();
            $data->status   = $result['result'];
            $data->response = json_encode($result);
            $this->dao->update(TABLE_REQUESTLOG)->data($data)->where('id')->eq($logID)->exec();
            return true;
        }
    }

    /**
     * return response.
     * 返回响应信息。
     *
     * @param  string $result
     * @param  string $message
     * @param  array  $data
     * @param  int    $logID
     * @access public
     * @return void
     */
    public function saveResponse($result = 'fail', $message = '', $data = array(), $logID = 0)
    {
        $response = array('result' => $result, 'message' => $message, 'data' => $data);
        $this->updateRequestLog($logID, $response);

        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        die();
    }

    /**
     * Http.
     *
     * @param  string       $url
     * @param  string|array $data
     * @param  string       $method    GET|POST|PATCH
     * @param  string       $dataType  data|json
     * @param  array        $headers   Set request headers.
     * @param  array        $options   This is option and value pair, like CURLOPT_HEADER => true. Use curl_setopt function to set options.
     * @static
     * @access public
     * @return string
     */
    public function http($url, $data = array(), $method = 'POST', $dataType = 'data', $headers = array(), $options = array())
    {
        global $lang, $app;
        if(!extension_loaded('curl')) $this->saveResponse('fail', $lang->error->noCurlExt);

        if(!is_array($headers)) $headers = (array)$headers;
        $headers[] = "API-RemoteIP: " . zget($_SERVER, 'REMOTE_ADDR', '');
        if($dataType == 'json')
        {
            $headers[] = 'Content-Type: application/json;charset=utf-8';
            if(!empty($data)) $data = json_encode($data);
        }

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Sae T OAuth2 v0.1');

        curl_setopt($curl, CURLOPT_NOSIGNAL, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 5);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_ENCODING, "");
        curl_setopt($curl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLINFO_HEADER_OUT, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        if($options) curl_setopt_array($curl, $options);

        if(strpos($url, 'https://') !== false)
        {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        }

        if(!empty($data) and $method != 'GET')
        {
            if($method == 'POST')  curl_setopt($curl, CURLOPT_POST, true);
            if($method == 'PATCH') curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PATCH');
            if($dataType == 'build') $data = http_build_query($data);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }

        curl_setopt($curl, CURLOPT_URL, $url);
        $response = curl_exec($curl);
        curl_close($curl);

        return $response;
    }
}
