<?php
$config->installed       = true;
$config->debug           = 6;
$config->requestType     = 'PATH_INFO';
$config->timezone        = 'Asia/Shanghai';
$config->db->driver      = 'mysql';
$config->db->host        = '127.0.0.1';
$config->db->port        = '3305';
$config->db->name        = 'changhong';
$config->db->user        = 'root';
$config->db->encoding    = 'utf8mb4';
$config->db->password    = '122112';
$config->db->prefix      = 'zt_';
$config->webRoot         = getWebRoot();
$config->default->lang   = 'zh-cn';
$config->customSession   = true;

/* 禅道后端调试等级必须 ≧ 5。*/
$config->debug = 6;

/* 设置 zui3 资源路径，确保开发过程中永远使用最新版本的 zui3 */
$config->zuiPath = 'https://zui-dist.oop.cc/zentao/';

/* 设置性能门禁数据提交信息。*/
$config->zinTool             = array();
/* 设置性能门禁数据提交 API 地址：*/
$config->zinTool['guardApi'] = '///mongo-api.qc.oop.cc/api/v1/zentaopms/performance';
/* 当前开发者 Gitfox 账号（用户名）：*/
$config->zinTool['author']   = 'liumingliang'; //  记得换成自己的账号
/* 当前开发者 Git 提交的 Email：*/
$config->zinTool['email']    = 'liumingliang@chandao.com'; //  记得换成自己的账号

// $filter->default->get['zin'] = 'reg::word';

/*
 * 设置 Content Security Policy，
 * 允许向 *.oop.cc（即 $config->zinTool['guardApi'] 配置中的目标服务器）提交数据，
 * *.oop.cc 在当前情况下可以更精确的设置为 mongo-api.qc.oop.cc
 */
$config->CSPs = array("form-action 'self';connect-src 'self' *.oop.cc");

/**
 *
 * 通过 http://your-zentao-dev-server-url.com
 * 可以查看性能门禁数据，默认情况下会展示当天自己的最近 200
 * 条数据，可以通过页面上的参数筛选栏目来自定义查询。
 * 非开发同学可以访问 https://zin.oop.cc/dev/ 来查看性能门禁数据。
 * 更多说明参考： https://back.zcorp.cc/pms/doc-view-3578.html
 */