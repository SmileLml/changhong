<?php
/**
 * @param mixed[] $taskList
 */
public function getBasicMetrics($taskList)
{
    return $this->loadExtension('report')->getBasicMetrics($taskList);
}
/**
 * @param mixed[] $taskList
 */
public function buildBasicChartConfig($taskList)
{
    return $this->loadExtension('report')->buildBasicChartConfig($taskList);
}
/**
 * @param mixed[] $taskList
 * @param object $data
 * @param string $type
 */
public function getProgressMetrics($taskList, $data, $type = 'execution')
{
    return $this->loadExtension('report')->getProgressMetrics($taskList, $data, $type);
}
/**
 * @param mixed[] $taskList
 * @param object $data
 * @param string $type
 */
public function buildProgressChartConfig($taskList, $data, $type = 'execution')
{
    return $this->loadExtension('report')->buildProgressChartConfig($taskList, $data, $type);
}
/**
 * @param mixed[] $taskList
 * @param object $data
 */
public function getResourceMetrics($taskList, $data, $type = 'execution')
{
    return $this->loadExtension('report')->getResourceMetrics($taskList, $data, $type);
}
/**
 * @param mixed[] $taskList
 * @param object $data
 */
public function buildResourceChartConfig($taskList, $data, $type = 'execution')
{
    return $this->loadExtension('report')->buildResourceChartConfig($taskList, $data, $type);
}
