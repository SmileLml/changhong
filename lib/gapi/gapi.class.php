<?php
/**
 * 第三方接口基础封装类
 * 提供通用接口调用、日志记录、错误处理机制
 */
class gapi
{
    protected $config = [
        'apiKey' => '',         // 接口密钥
        'baseUrl' => '',        // 接口基础地址
        'timeout' => 30,        // 请求超时时间
        'logPath' => './logs/', // 日志存储目录
        'retry' => 2,           // 失败重试次数
        'verifySSL' => true     // SSL证书验证
    ];

    // 初始化时合并配置
    public function __construct($config = [])
    {
        global $app;
        $this->app = $app;

        $this->app->control->loadModel('requestlog');
        $this->config = array_merge($this->config, $config);
        $this->initLogDir();
    }

    /**
     * 发送GET请求（子类可直接调用）
     * @param  string $path 接口路径
     * @param  array  $params 请求参数
     * @return array
     */
    protected function get($path, $params = [])
    {
        return $this->sendRequest('GET', $path, ['query' => $params]);
    }

    /**
     * 发送POST请求（子类可直接调用）
     * @param  string $path 接口路径
     * @param  array  $data 提交数据
     * @return array
     */
    protected function post($path, $data = [])
    {
        return $this->sendRequest('POST', $path, ['form' => $data]);
    }

    /**
     * 核心请求方法
     *
     * @param  string  $method
     * @param  string  $path
     * @param  array   $options
     * @access private
     */
    private function sendRequest($method, $path, $options, $purpose = '')
    {
        try
        {
            $url     = $this->buildUrl($path);
            $options = $this->prepareRequestData($options);

            //$this->logRequest($method, $url, $options); // 请求日志

            $ch = curl_init();
            curl_setopt_array($ch, $this->buildCurlOptions($method, $url, $options));

            $response = $this->executeWithRetry($ch);
            $parsed   = $this->parseResponse($response);
            $timeInfo = curl_getinfo($ch);

            $this->logResponse($url, $parsed, $method, $options, $timeInfo, $purpose); // 响应日志
            return $parsed;

        }
        catch(\Exception $e)
        {
            $this->logError($e->getMessage(), ['method' => $method, 'path' => $path, 'options' => $options]);
            throw $e; // 抛出异常由子类处理
        }
    }

    /***************************************
     * 以下方法供子类重载实现定制化逻辑 *
     ***************************************/

    // 构建请求头（可重载添加鉴权等）
    protected function buildHeaders()
    {
        return ['Content-Type: application/x-www-form-urlencoded', 'Accept: application/json'];
    }

    // 请求参数预处理（可重载实现签名等）
    protected function prepareRequestData($data)
    {
        return $data; // 默认不处理
    }

    // 响应解析（可重载处理不同格式）
    protected function parseResponse($rawResponse)
    {
        return json_decode($rawResponse, true) ?: [];
    }

    /***************************************
     * 内部工具方法（通常无需子类修改） *
     ***************************************/

    private function buildUrl($path)
    {
        return rtrim($this->config['baseUrl'], '/') . '/' . ltrim($path, '/');
    }

    private function buildCurlOptions($method, $url, $options)
    {
        $opts = array();
        $opts[CURLOPT_URL]            = $method === 'GET' ? $url . '?' . http_build_query($options['query']) : $url;
        $opts[CURLOPT_RETURNTRANSFER] = true;
        $opts[CURLOPT_TIMEOUT]        = $this->config['timeout'];
        $opts[CURLOPT_SSL_VERIFYPEER] = $this->config['verifySSL'];
        $opts[CURLOPT_HTTPHEADER]     = $this->buildHeaders();

        if($method === 'POST')
        {
            $opts[CURLOPT_POST]       = true;
            $opts[CURLOPT_POSTFIELDS] = http_build_query($options['form']);
        }

        return $opts;
    }

    private function executeWithRetry($ch)
    {
        $retry = $this->config['retry'];
        do
        {
            $response = curl_exec($ch);
            if($response !== false) break;
            $retry--;
        }
        while($retry >= 0);

        if($response === false) throw new \Exception('Curl error: ' . curl_error($ch));
        curl_close($ch);
        return $response;
    }

    /***********************
     * 日志系统 *
     ***********************/

    private function logRequest($method, $url, $options)
    {
        $log = sprintf("[%s] REQUEST: %s %s\nParams: %s\n",
            date('Y-m-d H:i:s'),
            $method,
            $url,
            json_encode($options, JSON_UNESCAPED_SLASHES)
        );
        file_put_contents($this->getLogFile('request'), $log, FILE_APPEND);
    }

    private function logResponse($url, $response, $method, $options, $timeInfo, $purpose)
    {
        $responseData = array();
        $responseData['url']          = $url;
        $responseData['responseTime'] = round($timeInfo['total_time'] * 1000, 2);
        $responseData['statusCode']   = $response['code'];
        $responseData['status']       = $response['code'] == '200' ? 'success' : 'fail';
        $responseData['clientIP']     = $timeInfo['primary_ip'];
        $responseData['requestUser']  = $this->app->user->account;
        $responseData['requestTime']  = date('Y-m-d H:i:s');
        $responseData['params']       = json_encode($options);
        $responseData['response']     = json_encode($response);
        $responseData['purpose']      = $purpose;

        $this->app->control->requestlog->saveRequestLog($responseData);
    }

    private function logResponseBak($url, $response)
    {
        $log = sprintf("[%s] RESPONSE: %s\n%s\n\n",
            date('Y-m-d H:i:s'),
            $url,
            json_encode($response, JSON_UNESCAPED_SLASHES)
        );
        file_put_contents($this->getLogFile('response'), $log, FILE_APPEND);
    }

    private function logError($message, $context = [])
    {
        $log = sprintf("[%s] ERROR: %s\nContext: %s\nTrace:\n%s\n\n",
            date('Y-m-d H:i:s'),
            $message,
            json_encode($context),
            (new \Exception())->getTraceAsString()
        );
        file_put_contents($this->getLogFile('error'), $log, FILE_APPEND);
    }

    private function getLogFile($type)
    {
        return $this->config['logPath'] . date('Y-m-d') . "_gapi_{$type}.log";
    }

    private function initLogDir()
    {
        if(!is_dir($this->config['logPath'])) mkdir($this->config['logPath'], 0755, true);
    }
}
