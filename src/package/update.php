<?php
// 更新脚本文件
$updates = [];
foreach ($updates as $version => $fn) {
    if (version_compare($oldversion, $version, '<')) {
        $fn();
    }
}
