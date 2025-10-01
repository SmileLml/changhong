ALTER TABLE zt_ai_prompt ADD COLUMN triggerControl text DEFAULT NULL COMMENT '触发动作' AFTER SOURCE;

CREATE TABLE `zt_aiscore_rules` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `objectType` varchar(30) DEFAULT '' comment '对象类型',
    `field` varchar(30) NOT NULL DEFAULT '' comment '字段名',
    `rules` text comment '评分规则',
    `editDate` datetime NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `zt_aiscore_result` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `objectType` varchar(30) DEFAULT '' comment '对象类型',
    `objectID`  mediumint(8) unsigned NOT NULL comment '对象ID',
    `action` varchar(30) DEFAULT '' comment '触发动作',
    `field` varchar(30) NOT NULL DEFAULT '' comment '字段名,为空时表示对该对象的整体评分',
    `score` varchar(30) NOT NULL DEFAULT '' comment '分值',
    `times` int(11) NOT NULL DEFAULT 1 comment '次数',
    `createBy` varchar(30) NOT NULL DEFAULT '',
    `createDate` datetime NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `zt_aiscore_weight` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `promptID`  mediumint(8) unsigned NOT NULL comment '题词ID',
    `field` varchar(30) NOT NULL DEFAULT '' comment '字段名',
    `weight` varchar(30) NOT NULL DEFAULT '0' comment '权重',
    `createBy` varchar(30) NOT NULL DEFAULT '',
    `createDate` datetime NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
