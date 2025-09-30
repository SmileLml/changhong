CREATE TABLE IF NOT EXISTS `zt_requestlog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(200) DEFAULT NULL,
  `responseTime` int(11) NOT NULL COMMENT '响应时间(ms)',
  `statusCode` int(11) NOT NULL COMMENT '状态码',
  `status` varchar(10) DEFAULT NULL COMMENT '状态',
  `clientIP` varchar(45) DEFAULT NULL COMMENT '客户端IP',
  `requestUser` varchar(45) DEFAULT NULL COMMENT '请求人',
  `requestTime` datetime NOT NULL COMMENT '请求时间',
  `requestType` varchar(10) DEFAULT NULL COMMENT '请求类型',
  `params` longtext,
  `response` longtext,
  `purpose` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
);
