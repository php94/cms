<?php

declare(strict_types=1);

namespace App\Php94\Cms\Http\Model;

use App\Php94\Admin\Http\Common;
use PHP94\Help\Response;
use PHP94\Facade\Db;
use PHP94\Help\Request;

class Delete extends Common
{
    public function get()
    {
        $model = Db::get('php94_cms_model', '*', [
            'id' => Request::get('id'),
        ]);
        Db::drop('php94_cms_content_' . $model['name']);
        Db::delete('php94_cms_model', [
            'id' => Request::get('id'),
        ]);
        Db::delete('php94_cms_field', [
            'model_id' => $model['id'],
        ]);
        return Response::success('操作成功！');
    }
}
