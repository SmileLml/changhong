<?php
/**
 * The query class file of zin lib.
 *
 * @copyright   Copyright 2024 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @author      Hao Sun <sunhao@easycorp.ltd>
 * @package     zin
 * @version     $Id
 * @link        https://www.zentao.net
 */

namespace zin;

require_once __DIR__ . DS . 'wg.class.php';
require_once __DIR__ . DS . 'selector.func.php';
require_once __DIR__ . DS . 'query.class.php';

/**
 * The query class.
 */
class query
{
    /**
     * The selector object list.
     * @var mixed[]
     */
    public $selectors;

    /**
     * The command list.
     * @var mixed[]
     */
    public $commands = array();

    /**
     * Construct the query instance.
     *
     * @param  object|string|array - $selectors
     * @access public
     */
    public function __construct($selectors)
    {
       $this->selectors = parseSelectors($selectors, true);
       context::current()->addQuery($this);
    }

    /**
     * Magic method for adding commands.
     *
     * @access public
     * @param  string $name - Property name.
     * @param  array  $args - Property values.
     * @return query
     */
    public function __call($name, $args)
    {
        skipRenderInGlobal($args);
        $this->commands[] = array($name, $args);
        return $this;
    }

    /**
     * Debug info.
     *
     * @access public
     * @return array
     */
    public function __debugInfo()
    {
        return array
        (
            'selectors' => $this->selectors,
            'commands'  => $this->commands
        );
    }

    public function isRoot()
    {
        return count($this->selectors) === 1 && $this->selectors[0]->tag === 'root';
    }
}

/**
 * Create a query object.
 *
 * @param  object|string|array $selectors
 * @return query
 */
function query($selectors)
{
    return new query($selectors);
}
