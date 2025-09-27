<?php
/**
 * @param string $title
 * @param mixed[] $dataMap
 * @param int $xAxis
 * @param int $yAxis
 * @param int $index
 * @param int $itemCount
 * @param string $label
 */
public function buildPieChartConfig($title, $dataMap, &$xAxis = 0, &$yAxis = 0, $index = 0, $itemCount = 3, $label = '')
{
    return $this->loadExtension('screen')->buildPieChartConfig($title, $dataMap, $xAxis, $yAxis, $index, $itemCount, $label);
}

/**
 * @param string $title
 * @param mixed[] $dataMap
 * @param int $yAxis
 * @param string $type
 * @param int $index
 * @param int $itemNum
 * @param int $height
 */
public function buildBarChartConfig($title, $dataMap, &$yAxis = 0, $type = 'cluBarX', $index = 0, $itemNum = 1, $height = 0)
{
    return $this->loadExtension('screen')->buildBarChartConfig($title, $dataMap, $yAxis, $type, $index, $itemNum, $height);
}

/**
 * @param int $height
 * @param int $yAxis
 * @param mixed[] $attr
 * @param int $multiplier
 * @param int $divider
 */
public function addBorderChart($height, $yAxis, $attr, $multiplier = 10, $divider = 0)
{
    return $this->loadExtension('screen')->addBorderChart($height, $yAxis, $attr, $multiplier, $divider);
}

/**
 * @param string $title
 * @param int $xAxis
 * @param int $yAxis
 */
public function buildTitleChartConfig($title, $xAxis, $yAxis)
{
    return $this->loadExtension('screen')->buildTitleChartConfig($title, $xAxis, $yAxis);
}

/**
 * @param string $title
 * @param string $subTitle
 * @param int $xAxis
 * @param int $yAxis
 * @param int $index
 * @param int $width
 * @param int $gap
 * @param string $tips
 */
public function buildTextChartConfig($title, $subTitle, &$xAxis, &$yAxis, $index = 0, $width = 0, $gap = 0, $tips = '')
{
    return $this->loadExtension('screen')->buildTextChartConfig($title, $subTitle, $xAxis, $yAxis, $index, $width, $gap, $tips);
}

/**
 * @param string $title
 * @param float $rate
 * @param int $xAxis
 * @param int $yAxis
 * @param int $index
 * @param string $tips
 * @param int $lineNum
 */
public function buildWaterChartConfig($title, $rate, &$xAxis, &$yAxis, $index = 0, $tips = '', $lineNum = 2)
{
    return $this->loadExtension('screen')->buildWaterChartConfig($title, $rate, $xAxis, $yAxis, $index, $tips, $lineNum);
}

/**
 * @param int|float $xAxis
 * @param int|float $yAxis
 * @param string $tips
 */
public function addHelperChart($xAxis, $yAxis, $tips)
{
    return $this->loadExtension('screen')->addHelperChart($xAxis, $yAxis, $tips);
}

/**
 * @param string $title
 * @param mixed[] $headers
 * @param mixed[] $dataset
 * @param int $yAxis
 * @param int $lineNum
 * @param mixed[] $rowspan
 * @param string $noDataTip
 * @param string $titleTip
 */
public function buildTableChartConfig($title, $headers, $dataset, &$yAxis = 0, $lineNum = 0, $rowspan = array(), $noDataTip = '', $titleTip = '')
{
    return $this->loadExtension('screen')->buildTableChartConfig($title, $headers, $dataset, $yAxis, $lineNum, $rowspan, $noDataTip, $titleTip);
}

/**
 * @param mixed[] $contents
 * @param int $xAxis
 * @param int $yAxis
 * @param int $width
 * @param int $height
 */
public function buildTextGroupChartConfig($contents, &$xAxis, &$yAxis, $width = 0, $height = 0)
{
    return $this->loadExtension('screen')->buildTextGroupChartConfig($contents, $xAxis, $yAxis, $width, $height);
}

/**
 * @param string $title
 * @param mixed[] $dataMap
 * @param int $xAxis
 * @param int $yAxis
 */
public function buildSunburstChartConfig($title, $dataMap, &$xAxis = 0, &$yAxis = 0, $tips = '')
{
    return $this->loadExtension('screen')->buildSunburstChartConfig($title, $dataMap, $xAxis, $yAxis, $tips);
}
