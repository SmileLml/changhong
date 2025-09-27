<?php
/**
 * @param mixed[] $bugs
 * @param object $data
 * @param string $type
 */
public function getBugBasicMetrics($bugs, $data, $type = 'execution')
{
    return $this->loadExtension('report')->getBugBasicMetrics($bugs, $data, $type);
}
/**
 * @param mixed[] $bugs
 * @param object $data
 * @param string $type
 */
public function buildBasicBugConfig($bugs, $data, $type = 'execution')
{
    return $this->loadExtension('report')->buildBasicBugConfig($bugs, $data, $type);
}
/**
 * @param mixed[] $bugs
 * @param object $data
 * @param string $type
 */
public function getBugProgressMetrics($bugs, $data, $type = 'execution')
{
    return $this->loadExtension('report')->getBugProgressMetrics($bugs, $data, $type);
}
/**
 * @param mixed[] $bugs
 * @param object $data
 * @param string $type
 */
public function buildProgressBugConfig($bugs, $data, $type = 'execution')
{
    return $this->loadExtension('report')->buildProgressBugConfig($bugs, $data, $type);
}
/**
 * @param mixed[] $userPairs
 * @param mixed[] $userData
 */
public function processUserStats($userPairs, $userData)
{
    return $this->loadExtension('report')->processUserStats($userPairs, $userData);
}
