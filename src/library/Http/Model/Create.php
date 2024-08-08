<?php

declare(strict_types=1);

namespace App\Php94\Cms\Http\Model;

use App\Php94\Admin\Http\Common;
use App\Php94\Cms\Model\ModelProvider;
use PHP94\Response;
use PHP94\Db;
use PHP94\Form\Field\Option;
use PHP94\Form\Field\Select;
use PHP94\Form\Form;
use PHP94\Form\Field\Text;
use PHP94\Request;

class Create extends Common
{
    public function get(
        ModelProvider $modelProvider
    ) {
        $form = new Form('添加模型');
        $typefield = new Select('类型', 'type');
        foreach ($modelProvider->all() as $type) {
            $typefield->addItem(new Option($type::getTitle(), $type));
        }
        $form->addItem(
            (new Text('标题', 'title')),
            (new Text('名称', 'name'))->setHelp('名称只能由字母开头，字母、数字、下划线组成，不超过20个字符'),
            $typefield,
        );
        return $form;
    }

    public function post()
    {
        $name = Request::post('name');

        if (!preg_match('/^[A-Za-z][A-Za-z0-9_]{0,18}[A-Za-z0-9]$/', $name)) {
            return Response::error('名称只能由字母开头，字母、数字、下划线组成，不超过20个字符');
        }

        if (Db::get('php94_cms_model', '*', [
            'name' => $name,
        ])) {
            return Response::error('模型名称不能重复');
        }

        $type = Request::post('type', '');

        Db::insert('php94_cms_model', [
            'title' => Request::post('title'),
            'type' => $type,
            'name' => $name
        ]);
        $model_id = Db::id();

        Db::create('php94_cms_content_' . $name, [
            "id" => [
                "INT",
                "NOT NULL",
                "AUTO_INCREMENT"
            ],
            "PRIMARY KEY (<id>)"
        ], [
            "ENGINE" => "MyISAM",
            "AUTO_INCREMENT" => 1
        ]);

        $type::onCreate(Db::get('php94_cms_model', '*', [
            'id' => $model_id,
        ]));

        return Response::success('操作成功！');
    }
}
