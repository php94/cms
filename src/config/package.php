<?php

use PHP94\Package;

return [
    'install' => function () {
        $sql = <<<'str'
DROP TABLE IF EXISTS `prefix_php94_cms_model`;
CREATE TABLE `prefix_php94_cms_model` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
    `title` varchar(255) NOT NULL COMMENT '标题',
    `name` varchar(255) NOT NULL COMMENT '名称',
    `type` varchar(255) NOT NULL COMMENT '类型',
    PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT COMMENT='内容模型表';
DROP TABLE IF EXISTS `prefix_php94_cms_field`;
CREATE TABLE `prefix_php94_cms_field` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
    `model_id` int(10) unsigned NOT NULL COMMENT '模型ID',
    `system` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '系统字段',
    `group` varchar(255) NOT NULL COMMENT '分组',
    `title` varchar(255) NOT NULL COMMENT '标题',
    `name` varchar(255) NOT NULL COMMENT '字段',
    `help` text NOT NULL COMMENT '后台提示信息',
    `editable` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '是否允许后台编辑',
    `show` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否允许后台列表显示',
    `tpl` text NOT NULL COMMENT '后台列表显示模板',
    `priority` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
    `type` varchar(255) NOT NULL COMMENT '类型',
    `options` json NOT NULL COMMENT '设置数据',
    PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT COMMENT='模型字段表';
str;
        Package::execSql($sql);
    },
    'unInstall' => function () {
        $stm = Package::querySql('select * from prefix_php94_cms_model');
        $tables = [];
        while ($item = $stm->fetch(PDO::FETCH_ASSOC)) {
            $tables[] = 'DROP TABLE IF EXISTS `prefix_php94_cms_content_' . $item['name'] . '`;';
        }
        if ($tables) {
            Package::execSql(implode('', $tables));
        }

        $sql = <<<'str'
DROP TABLE IF EXISTS `prefix_php94_cms_model`;
DROP TABLE IF EXISTS `prefix_php94_cms_field`;
str;
        Package::execSql($sql);
    },
    'update' => function (string $oldversion) {
        $updates = [];
        foreach ($updates as $version => $fn) {
            if (version_compare($oldversion, $version, '<')) {
                $fn();
            }
        }
    },
];
