<?php
// 卸载脚本文件

use PHP94\Package;

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
