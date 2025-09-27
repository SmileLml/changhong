<?php
/**
 * The helper methods file of zin of ZenTaoPMS.
 *
 * @copyright   Copyright 2023 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @author      Hao Sun <sunhao@easycorp.ltd>
 * @package     zin
 * @version     $Id
 * @link        https://www.zentao.net
 */

namespace zin;

function setWgVer($ver, $names = null)
{
    global $config;
    $zinConfig = $config->zin;

    if(is_string($names)) $names = explode(',', $names);
    if(!is_array($names)) return;

    foreach($names as $name)
    {
        $name = trim($name);
        if(!empty($name)) continue;

        $zinConfig->wgVerMap[$name] = $ver;
    }
}

function getWgVer($name)
{
    global $config;

    return isset($config->zin->verMap[$name]) ? $config->zin->verMap[$name] : $config->zin->wgVer;
}

function createWg($name, $args, $fallbackWgName = null)
{
    $name  = strtolower($name);
    $wgVer = getWgVer($name);
    $wgName = "\\zin\\$name";

    if(!class_exists($wgName))
    {
        if(in_array($name, h::$h5Tags))
        {
            return h::$name($args);
        }

        include_once dirname(__DIR__) . DS . 'wg' . DS . $name . DS . "v$wgVer.php";
    }

    if(!class_exists($wgName) && $fallbackWgName)
    {
        $fallbackWgName = "\\zin\\$fallbackWgName";
        if(class_exists($fallbackWgName)) $wgName = $fallbackWgName;
    }

    return class_exists($wgName) ? (new $wgName($args)) : $wgName($args);
}

function requireWg($name, $wgVer = '')
{
    $name   = strtolower($name);
    $wgName = "\\zin\\$name";

    if(class_exists($wgName)) return;

    $wgVer  = empty($wgVer) ? getWgVer($name) : $wgVer;

    require_once dirname(__DIR__) . DS . 'wg' . DS . $name . DS . "v$wgVer.php";

    return $wgName;
}
