<?php

declare(strict_types=1);

namespace App\Php94\Cms\Http\Model;

use App\Php94\Admin\Http\Common;
use PHP94\Help\Response;
use PHP94\Facade\Db;
use PHP94\Form\Field\Hidden;
use PHP94\Form\Field\Text;
use PHP94\Form\Form;
use PHP94\Help\Request;

class Update extends Common
{
    public function get()
    {
        $model = Db::get('php94_cms_model', '*', [
            'id' => Request::get('id'),
        ]);
        $form = new Form('编辑模型');
        $form->addItem(
            (new Hidden('id', $model['id'])),
            (new Text('标题', 'title', $model['title'])),
        );
        return $form;
    }

    public function post()
    {
        $model = Db::get('php94_cms_model', '*', [
            'id' => Request::post('id'),
        ]);

        Db::update('php94_cms_model', [
            'title' => Request::post('title', '')
        ], [
            'id' => $model['id'],
        ]);

        return Response::success('操作成功！');
    }
}
