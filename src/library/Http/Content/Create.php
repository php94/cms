<?php

declare(strict_types=1);

namespace App\Php94\Cms\Http\Content;

use App\Php94\Admin\Http\Common;
use PHP94\Help\Response;
use PHP94\Facade\Db;
use PHP94\Form\Form;
use PHP94\Form\Field\Hidden;
use PHP94\Help\Request;

class Create extends Common
{
    public function get()
    {
        if (!$model = Db::get('php94_cms_model', '*', [
            'id' => Request::get('model_id'),
        ])) {
            return Response::error('模型不存在！');
        }

        $form = new Form('创建内容');
        $form->addItem((new Hidden('model_id', $model['id'])));

        foreach (Db::select('php94_cms_field', '*', [
            'model_id' => $model['id'],
            'editable' => 1,
            'ORDER' => [
                'priority' => 'DESC',
                'id' => 'ASC',
            ],
        ]) as $field) {
            $field = array_merge(json_decode($field['options'], true), $field);
            $field['type']::onCreateContentForm($form, $field);
        }

        return $form;
    }

    public function post()
    {
        if (!$model = Db::get('php94_cms_model', '*', [
            'id' => Request::post('model_id'),
        ])) {
            return Response::error('模型不存在！');
        }

        $data = [];
        foreach (Db::select('php94_cms_field', '*', [
            'model_id' => $model['id'],
            'editable' => 1,
        ]) as $field) {
            $field = array_merge(json_decode($field['options'], true), $field);
            $field['type']::onCreateContent($data, $field);
        }
        Db::insert('php94_cms_content_' . $model['name'], $data);
        return Response::success('操作成功！');
    }
}
