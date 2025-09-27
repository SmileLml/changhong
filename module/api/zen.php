<?php
/**
 * The zen file of api module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian<tianshujie@easycorp.ltd>
 * @package     api
 * @link        https://www.zentao.net
 */
class apiZen extends api
{
    /**
     * 解析cookie里的docSpaceParam值。
     * Parse docSpaceParam cookie.
     *
     * @param  array     $libs
     * @param  int       $libID
     * @param  string    $type
     * @param  int       $objectID
     * @param  int       $moduleID
     * @param  string    $spaceType
     * @param  int       $release
     * @access protected
     * @return void
     */
    protected function parseDocSpaceParam($libs, $libID, $type, $objectID, $moduleID, $spaceType, $release)
    {
        if($this->cookie->docSpaceParam) $docParam = json_decode($this->cookie->docSpaceParam);
        if(isset($docParam) && !(in_array($docParam->type, array('product', 'project')) && $docParam->objectID == 0))
        {
            $type       = $docParam->type;
            $objectID   = $docParam->objectID;
            $libID      = $docParam->libID;
            $moduleID   = $docParam->moduleID;
            $browseType = $docParam->browseType;
            $param      = $docParam->param;
            $spaceType  = $docParam->type;

            list($libs, $libID, $object, $objectID, $objectDropdown) = $this->doc->setMenuByType($type, $objectID, $libID);
            $libTree = $this->doc->getLibTree($libID, $libs, $type, $moduleID, $objectID, $browseType, $param);
        }
        else
        {
            $objectDropdown = $this->generateLibsDropMenu($libs[$libID], $release);
            $libTree = $this->doc->getLibTree($libID, $libs, $spaceType, $moduleID);
        }

        $this->view->type           = $type;
        $this->view->objectType     = $type;
        $this->view->objectID       = $objectID;
        $this->view->libID          = $libID;
        $this->view->moduleID       = $moduleID;
        $this->view->libTree        = $libTree;
        $this->view->objectDropdown = $objectDropdown;
        $this->view->spaceType      = $spaceType;
    }

    /**
     * 组装页面左上角下拉菜单的数据。
     * Generate api doc index page dropMenu
     *
     * @param  object $lib
     * @param  int    $version
     * @access public
     * @return array
     */
    protected function generateLibsDropMenu($lib, $version = 0)
    {
        if(empty($lib)) return '';

        $objectTitle = $this->lang->api->noLinked;
        $objectType  = 'nolink';
        $objectID    = 0;
        if($lib->product)
        {
            $objectType = 'product';
            $objectID   = $lib->product;
            $product    = $this->loadModel('product')->getByID($objectID);
            $objectTitle = zget($product, 'name', '');
        }
        elseif($lib->project)
        {
            $objectType  = 'project';
            $objectID    = $lib->project;
            $project     = $this->loadModel('project')->getByID($objectID);
            $objectTitle = zget($project, 'name', '');
        }

        $objectDropdown['text'] = $objectTitle;
        $objectDropdown['link'] = helper::createLink('api', 'ajaxGetDropMenu', "objectType=$objectType&objectID=$objectID&libID=$lib->id&version=$version");
        return $objectDropdown;
    }

    /**
     * 解析请求地获得请求的详细信息。
     * Get the details of the method by file path.
     *
     * @param  string    $filePath
     * @param  string    $ext
     * @access protected
     * @return object
     */
    protected function getMethod($filePath, $ext = '')
    {
        $fileName   = dirname($filePath);
        $className  = basename(dirname(dirname($filePath)));
        $methodName = basename($filePath);

        if(!class_exists($className)) helper::import($fileName);
        $method = new ReflectionMethod($className . $ext, $methodName);
        $data   = new stdClass();

        $data->startLine  = $method->getStartLine();
        $data->endLine    = $method->getEndLine();
        $data->comment    = $method->getDocComment();
        $data->parameters = $method->getParameters();
        $data->className  = $className;
        $data->methodName = $methodName;
        $data->fileName   = $fileName;
        $data->post       = false;

        $file = file($fileName);
        for($i = $data->startLine - 1; $i <= $data->endLine; $i++)
        {
            if(strpos($file[$i], '$this->post') or strpos($file[$i], 'fixer::input') or strpos($file[$i], '$_POST'))
            {
                $data->post = true;
            }
        }
        return $data;
    }

    /**
     * 对指定模块下的指定方法进行调用并返回请求结果。
     * Request the api.
     *
     * @param  string    $moduleName
     * @param  string    $methodName
     * @param  string    $action     extendModel | extendControl
     * @access protected
     * @return array
     */
    protected function request($moduleName, $methodName, $action)
    {
        $host  = common::getSysURL();
        $param = '';
        if($action == 'extendModel')
        {
            /* 对model的函数进行调用。 */
            if(!isset($_POST['noparam']))
            {
                foreach($_POST as $key => $value) $param .= ',' . $key . '=' . $value;
                $param = ltrim($param, ',');
            }
            $url  = rtrim($host, '/') . inlink('getModel',  "moduleName=$moduleName&methodName=$methodName&params=$param", 'json');
            $url .= strpos($url, '?') === false ? '?' : '&';
            $url .= $this->config->sessionVar . '=' . session_id();
        }
        else
        {
            /* 对control的函数进行调用。 */
            if(!isset($_POST['noparam']))
            {
                foreach($_POST as $key => $value) $param .= '&' . $key . '=' . $value;
                $param = ltrim($param, '&');
            }
            $url  = rtrim($host, '/') . helper::createLink($moduleName, $methodName, $param, 'json');
            $url .= strpos($url, '?') === false ? '?' : '&';
            $url .= $this->config->sessionVar . '=' . session_id();
        }

        /* Unlock session. After new request, restart session. */
        session_write_close();
        $content = file_get_contents($url);
        session_start();

        return array('url' => $url, 'content' => $content);
    }
}
