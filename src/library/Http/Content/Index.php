<?php

declare(strict_types=1);

namespace App\Php94\Cms\Http\Content;

use App\Php94\Admin\Http\Common;
use PHP94\Db;
use PHP94\Template;
use PHP94\Request;
use PHP94\Response;

class Index extends Common
{
    public function get()
    {
        $data = [];
        $data['models'] = Db::select('php94_cms_model', '*');

        if (!$model = Db::get('php94_cms_model', '*', [
            'id' => Request::get('model_id', 0),
        ])) {
            return Response::error('模型不存在');
        }
        $data['model'] = $model;

        $fields = Db::select('php94_cms_field', '*', [
            'model_id' => $model['id'],
            'ORDER' => [
                'priority' => 'DESC',
                'id' => 'ASC',
            ],
        ]);
        foreach ($fields as &$vo) {
            $vo = array_merge(json_decode($vo['options'], true), $vo);
        }
        unset($vo);
        $data['fields'] = $fields;

        $where = [];
        foreach ($fields as $vo) {
            $vo['type']::onFilter($where, $vo);
        }
        $data['total'] = Db::count('php94_cms_content_' . $model['name'], '*', $where);

        $data['page'] = Request::get('page', 1) ?: 1;
        $data['size'] = Request::get('size', 20) ?: 20;
        $data['pages'] = ceil($data['total'] / $data['size']) ?: 1;
        $where['LIMIT'] = [($data['page'] - 1) * $data['size'], $data['size']];
        $where['ORDER'] = Request::get('order', [
            'id' => 'DESC',
        ]);
        $data['contents'] = Db::select('php94_cms_content_' . $model['name'], '*', $where);

        return Template::render('content/index@php94/cms', $data);
    }
}
