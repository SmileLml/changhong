<?php
class screenReport extends reportModel
{
    /**
     * 构建饼状图图表配置。
     * Build chart config.
     *
     * @param  string    $title
     * @param  array     $dataMap
     * @param  int       $xAxis
     * @param  int       $yAxis
     * @param  int       $index
     * @param  int       $itemCount
     * @param  string    $label
     * @access public
     * @return array
     */
    public function buildPieChartConfig($title, $dataMap, &$xAxis = 0, &$yAxis = 0, $index = 0, $itemCount = 3, $label = '')
    {
        $itemWidth = $this->config->report->reportChart->oneThird + $this->config->report->reportChart->padding;
        if($itemCount == 1) $itemWidth = $this->config->report->reportChart->fullWidth;
        if($itemCount == 2) $itemWidth = $this->config->report->reportChart->oneHalf + $this->config->report->reportChart->padding;

        if(($itemCount == 3 && $index == 3) || ($itemCount == 2 && $index == 2))
        {
            $xAxis  = $this->config->report->reportChart->xAxis;
            $yAxis += $this->config->report->reportChart->pieHeight + $this->config->report->reportChart->padding * 7;
        }
        elseif($index)
        {
            $xAxis += $itemWidth;
        }

        $attr = array(
            'w' => $itemWidth,
            'h' => $this->config->report->reportChart->pieHeight + $this->config->report->reportChart->padding,
            'x' => $xAxis,
            'y' => $yAxis - $this->config->report->reportChart->padding * 2
        );

        $settings = array();
        $options  = array(
            'legend.show'                              => false,
            'tooltip.show'                             => true,
            'tooltip.formatter'                        => "{b} : {d}%",
            'series.0.outline.show'                    => false,
            'series.0.name'                            => $label ? $label : $this->lang->task->report->taskNum,
            'series.0.bottom'                          => $this->config->report->reportChart->titleSize,
            'series.0.label.normal.textStyle.fontSize' => 10,
            'series.0.color'                           => $this->config->report->reportChart->pieColor
        );

        $list = array();
        $sort = array();
        foreach($dataMap as $data)
        {
            if(!isset($data['name']))  $data['name']  = current($data);
            if(!isset($data['count'])) $data['count'] = zget($data, 'value', 0);
            if(empty($data['count']) && empty($data['name'])) continue;

            if(empty($data['name'])) $data['name'] = $this->lang->null;
            $list[] = array('name' => $data['name'], 'value' => $data['count']);
            $sort[] = $data['count'];
        }

        array_multisort($sort, SORT_DESC, $list);
        $config = array(array('name', 'value'), $list);
        if(empty($config[1])) $config[1][] = array('name' => $this->lang->null, 'value' => 0);

        $settings[] = $this->addBorderChart($this->config->report->reportChart->pieHeight, $yAxis - $this->config->report->reportChart->padding, $attr, 15);
        $settings[] = $this->loadModel('screen')->genComponentFromData('pie', $title, $config, $attr, $options);
        $settings[] = $this->buildTitleChartConfig($title, $xAxis, $yAxis - $this->config->report->reportChart->padding * 2);
        return $settings;
    }

    /**
     * 构建柱状图图表配置。
     * Build chart config.
     *
     * @param  string $title
     * @param  array  $dataMap
     * @param  int    $yAxis
     * @param  string $type
     * @param  int    $index
     * @param  int    $itemNum
     * @param  int    $height
     * @access public
     * @return array
     */
    public function buildBarChartConfig($title, $dataMap, &$yAxis = 0, $type = 'cluBarX', $index = 0, $itemNum = 1, $height = 0)
    {
        $defaultH   = $this->config->report->reportChart->barHeight + $this->config->report->reportChart->padding * 8;
        $itemHeight = $defaultH;
        $widthMap   = array($this->config->report->reportChart->fullWidth, $this->config->report->reportChart->oneHalf + 10);
        $xAxis      = $this->config->report->reportChart->xAxis;
        if($index)  $xAxis = $this->config->report->reportChart->xAxis + $widthMap[$itemNum - 1] + $this->config->report->reportChart->padding;
        if($height) $itemHeight = $height;
        $attr = array(
            'w' => $widthMap[$itemNum - 1],
            'h' => $itemHeight,
            'x' => $xAxis,
            'y' => $yAxis - $this->config->report->reportChart->padding * 5
        );

        $settings = array();
        $options  = array(
            'legend.show'                              => false,
            'tooltip.show'                             => true,
            'yAxis.splitLine.lineStyle.color'          => 'rgb(235, 237, 243)',
            'xAxis.splitLine.lineStyle.color'          => 'rgb(235, 237, 243)',
            'title.show'                               => false,
            'grid.containLabel'                        => true,
            'grid.left'                                => '2%',
            'grid.right'                               => '4%',
            'series.0.outline.show'                    => false,
            'series.0.encode.x'                        => 'name',
            'series.0.encode.y'                        => 'value',
            'series.0.label.normal.textStyle.fontSize' => $this->config->report->reportChart->textSize,
        );

        if($type == 'cluBarY')
        {
            $options['series.0.encode.x']    = 'value';
            $options['series.0.encode.y']    = 'name';
            $options['yAxis.splitLine.show'] = false;
            $options['xAxis.splitLine.show'] = true;
        }

        $list = array();
        $sort = array();
        if($type != 'line')
        {
            foreach($dataMap as $code => $data)
            {
                if(!is_array($data)) $data = array('name' => $code, 'count' => $data);

                if(!isset($data['name']))  $data['name']  = current($data);
                if(!isset($data['count'])) $data['count'] = zget($data, 'value', 0);
                if(empty($data['count']) && empty($data['name'])) continue;

                if(empty($data['name'])) $data['name'] = $this->lang->null;
                $list[] = array('name' => $data['name'], 'value' => $data['count']);
                $sort[] = $data['count'];
            }
        }

        if($type == 'cluBarY') array_multisort($sort, SORT_ASC, $list);
        $config = array(array('name', 'value'), $list);
        if($type == 'line')
        {
            $config = $dataMap;
            $options['series.0.label.show'] = false;
            unset($options['series.0.encode.x']);
            unset($options['series.0.encode.y']);
        }
        if(empty($config[1])) $config[1][] = array('name' => $this->lang->null, 'value' => 0);

        $borderY    = $yAxis - $this->config->report->reportChart->padding;
        $multiplier = 12;
        if($height)
        {
            $diff        = $itemHeight - $defaultH;
            $multiplier += $diff / 20;
            $borderY    += $diff;
        }
        $settings[] = $this->addBorderChart($this->config->report->reportChart->barHeight, $borderY, $attr, $multiplier);
        $settings[] = $this->loadModel('screen')->genComponentFromData($type, $title, $config, $attr, $options);
        $settings[] = $this->buildTitleChartConfig($title, $xAxis, $yAxis - $this->config->report->reportChart->padding * 2);
        return $settings;
    }

    /**
     * 增加边框配置。
     * Add border config.
     *
     * @param  int        $height
     * @param  int        $yAxis
     * @param  array      $attr
     * @param  int        $multiplier
     * @access public
     * @return object
     * @param int $divider
     */
    public function addBorderChart($height, $yAxis, $attr, $multiplier = 10, $divider = 0)
    {
        $attr['x'] -= $this->config->report->reportChart->xAxis;
        $attr['y'] = $yAxis;
        $attr['h'] = $height;
        $borderOptions = $this->config->report->reportChart->border;
        $borderOptions['paddingX'] = ($attr['w'] + $this->config->report->reportChart->padding) / 2 - 20;
        $borderOptions['paddingY'] = $this->config->report->reportChart->padding * $multiplier;
        if($divider > 0)
        {
            $borderOptions['paddingY']    += 20;
            $borderOptions['dividerY']     = $divider;  // 垂直分割的份数
            $borderOptions['dividerWidth'] = 2;         // 分割线的宽度
            $borderOptions['dividerColor'] = "#E6ECF8"; // 分割线的颜色
        }
        return $this->loadModel('screen')->genComponentFromData('text', '', '', $attr, $borderOptions);
    }

    /**
     * 构建标题图表配置。
     * Build title chart config.
     *
     * @param  string    $title
     * @param  int       $xAxis
     * @param  int       $yAxis
     * @param  array     $help
     * @param  bool      $bolder
     * @access public
     * @return object
     */
    public function buildTitleChartConfig($title, $xAxis, $yAxis, $help = array(), $bolder = true)
    {
        $attr = array(
            'w' => $this->config->report->reportChart->fullWidth,
            'h' => $this->config->report->reportChart->textSize,
            'x' => $xAxis,
            'y' => $yAxis
        );

        $options = array('fontSize' => $this->config->report->reportChart->titleSize, 'fontColor' => '#000', 'textAlign' => 'left');
        if($bolder) $options['fontWeight'] = 'bold';

        if(!empty($help)) $options = array_merge($options, $help);
        return $this->loadModel('screen')->genComponentFromData('text', $title, $title, $attr, $options);
    }

    /**
     * 构建指标卡图表配置。
     * Build chart config.
     *
     * @param  string    $title
     * @param  string    $subTitle
     * @param  int       $xAxis
     * @param  int       $yAxis
     * @param  int       $index
     * @param  int       $width
     * @param  int       $gap
     * @param  string    $tips
     * @access public
     * @return array
     */
    public function buildTextChartConfig($title, $subTitle, &$xAxis, &$yAxis, $index = 0, $width = 0, $gap = 0, $tips = '')
    {
        if(!$width) $width = $this->config->report->reportChart->oneQuarter;
        if($index)  $xAxis += $width + $gap;

        $borderY = $yAxis - $this->config->report->reportChart->padding;
        $attr    = array(
            'w' => $width,
            'h' => $this->config->report->reportChart->textSize,
            'x' => $xAxis - $this->config->report->reportChart->padding,
            'y' => $yAxis
        );

        if(is_numeric($title)) $title = round($title, 2);

        $settings = array();
        $attr['x'] += $this->config->report->reportChart->padding;
        $settings[] = $this->addBorderChart($this->config->report->reportChart->textHeight, $borderY, $attr, 4);
        $options  = array('fontSize' => $this->config->report->reportChart->textSize, 'fontColor' => '#000', 'fontFamily' => $this->config->report->reportChart->fontFamily);
        $settings[] = $this->loadModel('screen')->genComponentFromData('text', $title, $title, $attr, $options);

        $attr['y'] = $attr['y'] + $this->config->report->reportChart->textSize + $this->config->report->reportChart->padding;
        $options     = array('fontSize' => $this->config->report->reportChart->titleSize, 'fontColor' => '#000', 'fontFamily' => $this->config->report->reportChart->fontFamily);
        $helpOptions = array('helpIcon' => 'HelpCircleOutline', 'helpPosition' => 'end', 'helpIconSize' => 18, 'helpIconColor' => '#52525B');
        if(!empty($tips))
        {
            $helpOptions['hint'] = $tips;
            $options = array_merge($options, $helpOptions);
        }
        $settings[] = $this->screen->genComponentFromData('text', $subTitle, $subTitle, $attr, $options);

        return $settings;
    }

    /**
     * 构建卡片组配置。
     * Build a deck config.
     *
     * @param  array  $contents
     * @param  int    $xAxis
     * @param  int    $yAxis
     * @param  int    $width
     * @param  int    $height
     * @access public
     * @return array
     */
    public function buildTextGroupChartConfig($contents, &$xAxis, &$yAxis, $width = 0, $height = 0)
    {
        $this->loadModel('screen');
        if(empty($width))  $width  = $this->config->report->reportChart->fullWidth;
        if(empty($height)) $height = $this->config->report->reportChart->textHeight;
        $group     = count($contents);
        $itemWidth = $width;
        if(!empty($group)) $itemWidth = $width / $group;

        $titleOptions    = array('fontSize' => $this->config->report->reportChart->textSize,  'fontColor' => '#000', 'fontFamily' => $this->config->report->reportChart->fontFamily);
        $subTitleOptions = array('fontSize' => $this->config->report->reportChart->titleSize, 'fontColor' => '#000', 'fontFamily' => $this->config->report->reportChart->fontFamily);
        $helpOptions     = array('helpIcon' => 'HelpCircleOutline', 'helpPosition' => 'end', 'helpIconSize' => 18, 'helpIconColor' => '#52525B');
        $attr            = array(
            'w' => $width,
            'h' => $this->config->report->reportChart->textSize,
            'x' => $xAxis,
            'y' => $yAxis + 10
        );
        $settings = array();
        $settings[] = $this->addBorderChart($height, $yAxis, $attr, 4, $group);
        $attr['w']  = $itemWidth;
        $attr['x'] -= $this->config->report->reportChart->xAxis;
        foreach($contents as $content)
        {
            $settings[] = $this->screen->genComponentFromData('text', $content->title, $content->title, $attr, $titleOptions);
            $attr['y'] += $this->config->report->reportChart->textSize + $this->config->report->reportChart->padding * 2;
            if(!empty($content->help))
            {
                $helpOptions['hint'] = $content->help;
                $subTitleOptions = array_merge($subTitleOptions, $helpOptions);
            }
            else
            {
                if(isset($subTitleOptions['helpIcon']))
                {
                    unset($subTitleOptions['helpIcon']);
                    unset($subTitleOptions['helpPosition']);
                    unset($subTitleOptions['helpIconSize']);
                    unset($subTitleOptions['helpIconColor']);
                    unset($subTitleOptions['hint']);
                }
            }
            $settings[] = $this->screen->genComponentFromData('text', $content->desc, $content->desc, $attr, $subTitleOptions);
            $attr['x'] += $itemWidth;
            $attr['y'] -= $this->config->report->reportChart->textSize + $this->config->report->reportChart->padding * 2;
        }

        return $settings;
    }

    /**
     * 构建水球图表配置。
     * Build chart config.
     *
     * @param  string    $title
     * @param  float     $rate
     * @param  int       $xAxis
     * @param  int       $yAxis
     * @param  int       $index
     * @param  string    $tips
     * @param  int       $lineNum
     * @access public
     * @return array
     */
    public function buildWaterChartConfig($title, $rate, &$xAxis, &$yAxis, $index = 0, $tips = '', $lineNum = 2)
    {
        $width = $lineNum == 3 ? $this->config->report->reportChart->oneThird : $this->config->report->reportChart->oneHalf;
        if($index) $xAxis += $width + $this->config->report->reportChart->padding;

        $baseAttr = array(
            'w' => $width + $this->config->report->reportChart->padding,
            'h' => $this->config->report->reportChart->waterHeight  + $this->config->report->reportChart->padding * 4,
            'x' => $xAxis,
            'y' => $yAxis - $this->config->report->reportChart->padding * 4
        );

        $borderY = $yAxis - $this->config->report->reportChart->padding;
        $settings = array();

        $textAttr = array(
            'w' => $baseAttr['w'],
            'h' => $baseAttr['h'],
            'x' => $baseAttr['x'],
            'y' => $baseAttr['y'] + $this->config->report->reportChart->waterHeight - $this->config->report->reportChart->padding
        );

        $borderAttr = array(
            'w' => $textAttr['w'],
            'h' => $textAttr['h'],
            'x' => $textAttr['x'] + $this->config->report->reportChart->padding,
            'y' => $textAttr['y']
        );

        $settings[] = $this->addBorderChart($this->config->report->reportChart->waterHeight, $borderY, $borderAttr);

        $options = array(
            'title.show'                                 => false,
            'series.0.outline.show'                      => false,
            'series.0.label.normal.textStyle.fontSize'   => $this->config->report->reportChart->textSize,
            'series.0.label.normal.textStyle.fontWeight' => 'normal',
            'series.0.label.normal.textStyle.round'      => 2,
            'series.0.color.0.type'                      => 'linear',
            'series.0.color.0.x'                         => 0,
            'series.0.color.0.y'                         => 0,
            'series.0.color.0.x2'                        => 0,
            'series.0.color.0.y2'                        => 1,
            'series.0.color.0.colorStops.0.offset'       => 0,
            'series.0.color.0.colorStops.0.color'        => '#4992ff',
            'series.0.color.0.colorStops.1.offset'       => 1,
            'series.0.color.0.colorStops.1.color'        => '#7cffb2',
            'series.0.color.0.globalCoord'               => false
        );
        $settings[] = $this->loadModel('screen')->genComponentFromData('waterpolo', $title, $rate, $baseAttr, $options);

        $options = array
        (
            'fontSize'      => $this->config->report->reportChart->titleSize,
            'fontColor'     => '#000',
            'fontFamily'    => $this->config->report->reportChart->fontFamily,
            'helpIcon'      => 'HelpCircleOutline',
            'helpPosition'  => 'end',
            'helpIconSize'  => 18,
            'helpIconColor' => '#52525B',
            'hint'          => $tips
        );

        $settings[] = $this->screen->genComponentFromData('text', $title, $title, $textAttr, $options);
        return $settings;
    }

    /**
     * 构建类型表格图表配置。
     * Build table chart config.
     *
     * @param  string $title
     * @param  array  $headers
     * @param  array  $dataset
     * @param  int    $yAxis
     * @param  int    $lineNum
     * @param  array  $rowspan
     * @param  string $noDataTip
     * @param  string $titleTip
     * @access public
     * @return array
     */
    public function buildTableChartConfig($title, $headers, $dataset, &$yAxis = 0, $lineNum = 0, $rowspan = array(), $noDataTip = '', $titleTip = '')
    {
        $xAxis = $this->config->report->reportChart->xAxis;
        $attr  = array(
            'w' => $this->config->report->reportChart->fullWidth,
            'h' => $this->config->report->reportChart->barHeight + $this->config->report->reportChart->textHeight,
            'x' => $xAxis,
            'y' => $yAxis
        );

        $settings = array();
        $options  = array(
            'colNum'    => $lineNum ? $lineNum + 1 : ($dataset ? count(current($dataset)) + 1 : 0),
            'rowNum'    => 6,
            'headerBGC' => '#fcfdfe',
            'bodyBGC'   => '#fff',
            'borderBGC' => '#e6ecf8',
            'fontColor' => '#000',
            'rowHeight' => 36
        );

        $textOptions = array();
        if(!empty($titleTip))
        {
            $textOptions = array(
                'helpIcon'      => 'HelpCircleOutline',
                'helpPosition'  => 'end',
                'helpIconSize'  => 18,
                'helpIconColor' => '#52525B',
                'hint'          => $titleTip
            );
        }
        $settings[] = $this->addBorderChart($attr['h'], $yAxis + $this->config->report->reportChart->padding, $attr, 15);
        $settings[] = $this->buildTitleChartConfig($title, $xAxis, $yAxis, $textOptions);

        $yAxis += $this->config->report->reportChart->padding * 3;
        if(!empty($dataset))
        {
            $attr['y']  = $yAxis;
            $attr['w'] -= $this->config->report->reportChart->xAxis + $this->config->report->reportChart->padding;
            $settings[] = $this->loadModel('screen')->genComponentFromData('table', $title, array($headers, array(), array(), $dataset, array(), $rowspan), $attr, $options);
        }
        else
        {
            if(!$noDataTip) $noDataTip = $this->lang->noData;
            $settings[] = $this->buildTitleChartConfig($noDataTip, $xAxis + $this->config->report->reportChart->oneHalf - $this->config->report->reportChart->padding * 10, $yAxis + $this->config->report->reportChart->padding * 9, array(), false);
        }

        return $settings;
    }

    /**
     * 生成旭日图颜色。
     * Generate sunburst color.
     *
     * @param  array $dataMap
     * @param  int   $level
     * @access public
     * @return array
     */
    public function processSunburstColor($dataMap, $level = 0)
    {
        $colorList = zget($this->config->report->reportChart->sunburstColor, $level, $this->config->report->reportChart->sunburstColor[0]);

        $index = 0;
        $count = count($colorList);
        foreach($dataMap as $key => $value)
        {
            if($index + 1 == $count) $index = 0;

            $dataMap[$key]['itemStyle']['color'] = $colorList[$index];
            if(isset($value['children'])) $dataMap[$key]['children'] = $this->processSunburstColor($value['children'], $level + 1);

            $index ++;
        }
        return $dataMap;
    }

    /**
     * 构建旭日图图表配置。
     * Build sunburst chart config.
     *
     * @param  string $title
     * @param  array  $dataMap
     * @param  int    $xAxis
     * @param  int    $yAxis
     * @param  string $tips
     * @access public
     * @return array
     */
    public function buildSunburstChartConfig($title, $dataMap, &$xAxis = 0, &$yAxis = 0, $tips = '')
    {
        $dataMap = $this->processSunburstColor($dataMap);
        $attr = array(
            'w' => $this->config->report->reportChart->fullWidth,
            'h' => $this->config->report->reportChart->oneHalf + $this->config->report->reportChart->padding,
            'x' => $xAxis,
            'y' => $yAxis - $this->config->report->reportChart->padding
        );

        $settings = array();
        $options  = array('title.show' => false, 'tooltip.show' => true);
        $settings[] = $this->addBorderChart($this->config->report->reportChart->oneHalf, $yAxis - $this->config->report->reportChart->padding * 2, $attr, 24);
        if($tips)
        {
            $options = array_merge($options, array(
                'fontSize'      => $this->config->report->reportChart->titleSize,
                'fontColor'     => '#000',
                'fontWeight'    => 'bold',
                'textAlign'     => 'left',
                'helpIcon'      => 'HelpCircleOutline',
                'helpPosition'  => 'end',
                'helpIconSize'  => 18,
                'helpIconColor' => '#52525B',
                'hint'          => $tips
            ));

            $textAttr = array(
                'x' => $xAxis,
                'w' => $attr['w'],
                'y' => $yAxis - $this->config->report->reportChart->padding * 2,
                'h' => $this->config->report->reportChart->textSize
            );
            $settings[] = $this->screen->genComponentFromData('text', $title, $title, $textAttr, $options);
        }
        else
        {
            $settings[] = $this->buildTitleChartConfig($title, $xAxis, $yAxis - $this->config->report->reportChart->padding * 2);
        }

        $attr['w']  = $this->config->report->reportChart->oneHalf;
        $attr['x'] += $this->config->report->reportChart->oneThird - $this->config->report->reportChart->padding * 10;
        if(empty($dataMap))
        {
            $settings[] = $this->buildTitleChartConfig($this->lang->noData, $xAxis + $this->config->report->reportChart->oneHalf - $this->config->report->reportChart->padding, $yAxis + $this->config->report->reportChart->padding * 20, array(), false);
        }
        else
        {
            $settings[] = $this->loadModel('screen')->genComponentFromData('sunburst', $title, $dataMap, $attr, $options);
        }
        return $settings;
    }
}
