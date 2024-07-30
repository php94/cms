<?php

use App\Php94\Cms\Http\Content\Index as ContentIndex;
use App\Php94\Cms\Http\Model\Index as ModelIndex;

return [
    'menus' => [[
        'title' => '模型管理',
        'node' => ModelIndex::class,
    ], [
        'title' => '内容管理',
        'node' => ContentIndex::class,
    ]],
    'widgets' => [],
];
