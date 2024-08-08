<?php

declare(strict_types=1);

namespace App\Php94\Cms\Http\Content;

use App\Php94\Admin\Http\Common;
use PHP94\Db;
use PHP94\Request;
use PHP94\Response;

class Delete extends Common
{
    public function post()
    {
        if (!$model = Db::get('php94_cms_model', '*', [
            'id' => Request::post('model_id'),
        ])) {
            return Response::error('模型不存在！');
        }
        Db::delete('php94_cms_content_' . $model['name'], [
            'id' => explode(',', Request::post('ids')),
        ]);
        return Response::success('操作成功！');
    }
}
