<?php

declare(strict_types=1);

namespace App\Php94\Cms\Http\Content;

use App\Php94\Admin\Http\Common;
use PHP94\Response;
use PHP94\Db;
use PHP94\Form\Form;
use PHP94\Form\Field\Hidden;
use PHP94\Request;

class Update extends Common
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

        $form = new Form('编辑内容');
        $form->addItem(
            (new Hidden('model_id', $model['id'])),
            (new Hidden('id', $content['id'])),
        );
        foreach (Db::select('php94_cms_field', '*', [
            'model_id' => $model['id'],
            'editable' => 1,
            'ORDER' => [
                'priority' => 'DESC',
                'id' => 'ASC',
            ],
        ]) as $field) {
            $field = array_merge(json_decode($field['options'], true), $field);
            $field['type']::onUpdateContentForm($form, $field, $content);
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
        if (!$content = Db::get('php94_cms_content_' . $model['name'], '*', [
            'id' => Request::post('id'),
        ])) {
            return Response::error('内容不存在！');
        }

        $data = [];
        foreach (Db::select('php94_cms_field', '*', [
            'model_id' => $model['id'],
            'editable' => 1,
        ]) as $field) {
            $field = array_merge(json_decode($field['options'], true), $field);
            $field['type']::onUpdateContent($data, $field);
        }
        Db::update('php94_cms_content_' . $model['name'], $data, [
            'id' => $content['id'],
        ]);
        return Response::success('操作成功！');
    }
}
