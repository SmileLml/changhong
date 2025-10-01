<?php

/**
 * Get table by module.
 *
 * @param  string $module
 * @access public
 * @return string
 */
public function getTableByModule($module)
{
    return $this->dao->select('`table`')->from(TABLE_WORKFLOW) ->where('module')->eq($module) ->fetch('table');
}