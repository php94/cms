<?php

declare(strict_types=1);

namespace App\Php94\Cms\Http\Field;

use App\Php94\Admin\Http\Common;
use PHP94\Help\Response;
use PHP94\Facade\Db;
use PHP94\Help\Request;

class Delete extends Common
{
    public function get()
    {
        $field = Db::get('php94_cms_field', '*', [
            'id' => Request::get('id'),
        ]);
        $model = Db::get('php94_cms_model', '*', [
            'id' => $field['model_id'],
        ]);

        $fields = Db::query('SHOW COLUMNS FROM <php94_cms_content_' . $model['name'] . '>')->fetchAll();
        $find = false;
        foreach ($fields as $vo) {
            if ($vo['Field'] == $field['name']) {
                $find = true;
                break;
            }
        }
        if ($find) {
            Db::query('ALTER TABLE <php94_cms_content_' . $model['name'] . '> DROP ' . $field['name']);
        }

        Db::delete('php94_cms_field', [
            'id' => Request::get('id'),
        ]);

        return Response::success('操作成功！');
    }
}
