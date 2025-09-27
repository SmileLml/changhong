<?php
class zahostZen extends zahost
{
    /**
     * 获取服务状态。
     * Get service status from ZAgent server.
     *
     * @param  object $host
     * @access public
     * @return array
     */
    public function getServiceStatus($host)
    {
        if(in_array($host->status, array('wait', 'offline'))) return $this->lang->zahost->init->serviceStatus;

        $result = json_decode(commonModel::http("http://{$host->extranet}:{$host->zap}/api/v1/service/check", json_encode(array('services' => 'all')), array(), array("Authorization:$host->tokenSN")));

        if(empty($result) || $result->code != 'success') return $this->lang->zahost->init->serviceStatus;

        return (array)$result->data;
    }
}

