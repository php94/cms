<?php

declare(strict_types=1);

namespace App\Php94\Cms\Http\Field;

use App\Php94\Admin\Http\Common;
use PHP94\Help\Response;
use PHP94\Facade\Db;
use PHP94\Help\Request;

class Priority extends Common
{
    public function get()
    {
        $type = Request::get('type');
        $field = Db::get('php94_cms_field', '*', [
            'id' => Request::get('id'),
        ]);

        $fields = Db::select('php94_cms_field', '*', [
            'model_id' => $field['model_id'],
            'ORDER' => [
                'priority' => 'DESC',
                'id' => 'ASC',
            ],
        ]);

        $count = Db::count('php94_cms_field', [
            'model_id' => $field['model_id'],
            'id[!]' => $field['id'],
            'priority[<=]' => $field['priority'],
            'ORDER' => [
                'priority' => 'DESC',
                'id' => 'ASC',
            ],
        ]);
        $change_key = $type == 'up' ? $count + 1 : $count - 1;

        if ($change_key < 0) {
            return Response::error('已经是最有一位了！');
        }
        if ($change_key > count($fields) - 1) {
            return Response::error('已经是第一位了！');
        }
        $fields = array_reverse($fields);
        foreach ($fields as $key => $vo) {
            if ($key == $change_key) {
                Db::update('php94_cms_field', [
                    'priority' => $count,
                ], [
                    'id' => $vo['id'],
                ]);
            } elseif ($key == $count) {
                Db::update('php94_cms_field', [
                    'priority' => $change_key,
                ], [
                    'id' => $vo['id'],
                ]);
            } else {
                Db::update('php94_cms_field', [
                    'priority' => $key,
                ], [
                    'id' => $vo['id'],
                ]);
            }
        }
        return Response::redirect($_SERVER['HTTP_REFERER']);
    }
}
