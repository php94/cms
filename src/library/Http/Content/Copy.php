<?php

declare(strict_types=1);

namespace App\Php94\Cms\Http\Content;

use App\Php94\Admin\Http\Common;
use PHP94\Help\Response;
use PHP94\Facade\Db;
use PHP94\Help\Request;

class Copy extends Common
{
    public function get()
    {
        if (!$model = Db::get('php94_cms_model', '*', [
            'id' => Request::get('model_id'),
        ])) {
            return Response::error('模型不存在！');
        }
        if (!$content = Db::get('php94_cms_content_' . $model['name'], '*', [
            'id' => Request::get('id'),
        ])) {
            return Response::error('内容不存在！');
        }

        unset($content['id']);
        Db::insert('php94_cms_content_' . $model['name'], $content);
        return Response::redirect($_SERVER['HTTP_REFERER'] ?? '');
    }
}
