<?php
/**
 * @param mixed[] $cases
 * @param object $data
 */
public function getBasicMetrics($cases, $data)
{
    return $this->loadExtension('report')->getBasicMetrics($cases, $data);
}
/**
 * @param mixed[] $cases
 * @param object $data
 */
public function buildBasicConfig($cases, $data)
{
    return $this->loadExtension('report')->buildBasicConfig($cases, $data);
}
