<?php
if(!isset($config->report->reportChart)) $config->report->reportChart = new stdclass();

$config->report->reportChart->sunburstHeight = 1880;
$config->report->reportChart->waterHeight    = 200;
$config->report->reportChart->pieHeight      = 500;
$config->report->reportChart->barHeight      = 400;
$config->report->reportChart->textSize       = 32;
$config->report->reportChart->titleSize      = 14;
$config->report->reportChart->textHeight     = 110;
$config->report->reportChart->padding        = 20;
$config->report->reportChart->xAxis          = 20;
$config->report->reportChart->oneHalf        = 920;
$config->report->reportChart->oneQuarter     = 470;
$config->report->reportChart->oneThird       = 607;
$config->report->reportChart->fullWidth      = 1880;
$config->report->reportChart->border         = array('borderWidth' => 2, 'borderColor' => '#E6ECF8');
$config->report->reportChart->fontFamily     = '-apple-system,Noto Sans,Helvetica Neue,Helvetica,Nimbus Sans L,Arial,Liberation Sans,PingFang SC,Hiragino Sans GB,Noto Sans CJK SC,Source Han Sans SC,Source Han Sans CN,Microsoft YaHei,Wenquanyi Micro Hei,WenQuanYi Zen Hei,ST Heiti,SimHei,WenQuanYi Zen Hei Sharp,sans-serif';
$config->report->reportChart->pieColor       = array('#5470c6', '#91cc75', '#fac858', '#ee6666', '#73c0de', '#3ba272', '#fc8452', '#9a60b4', '#ea7ccc');

$config->report->reportChart->sunburstColor[] = array('#8166ee', '#ff8058', '#fc5959', '#ff9f46', '#31bd85', '#37b2fe', '#3883fa', '#64758b');
$config->report->reportChart->sunburstColor[] = array('#a38cff', '#ffb29b', '#ff9292', '#ffbc7e', '#83d7b6', '#77cbff', '#8ec6ff', '#d2d6e5');
$config->report->reportChart->sunburstColor[] = array('#e4dff7', '#ffd5d5', '#ffe2d9', '#cdecff', '#d6f2e7', '#cdecff', '#d9eaff', '#eff1f3');
