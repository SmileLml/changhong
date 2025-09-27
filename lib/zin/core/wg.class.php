<?php
/**
 * The base widget class file of zin of ZenTaoPMS.
 *
 * @copyright   Copyright 2023 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @author      Hao Sun <sunhao@easycorp.ltd>
 * @package     zin
 * @version     $Id
 * @link        https://www.zentao.net
 */

namespace zin;

require_once __DIR__ . DS . 'node.class.php';

class wg extends node
{
    public static function getPageCSS()
    {
        return null; // No css
    }

    public static function getPageJS()
    {
        return null; // No js
    }

    protected static function checkPageResources()
    {
        $name = get_called_class();
        if(isset(static::$pageResources[$name])) return;

        static::$pageResources[$name] = true;

        $pageCSS = static::getPageCSS();
        $pageJS  = static::getPageJS();

        if(!empty($pageCSS)) context::css($pageCSS);
        if(!empty($pageJS))  context::js($pageJS);
    }

    /**
     * @var mixed[]
     */
    protected static $pageResources = array();
}

/**
 * Create an new widget.
 *
 * @return wg
 * @param mixed ...$args
 */
function wg(...$args)
{
    return new wg(...$args);
}
