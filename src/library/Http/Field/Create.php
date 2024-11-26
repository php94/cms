<?php

declare(strict_types=1);

namespace App\Php94\Cms\Http\Field;

use App\Php94\Admin\Http\Common;
use PHP94\Response;
use PHP94\Db;
use PHP94\Form\Form;
use PHP94\Form\Field\Text;
use PHP94\Form\Field\Hidden;
use PHP94\Form\Field\Radio;
use PHP94\Form\Field\Radios;
use PHP94\Form\Field\Textarea;
use PHP94\Request;

class Create extends Common
{
    public function get()
    {
        $model = Db::get('php94_cms_model', '*', [
            'id' => Request::get('model_id'),
        ]);

        $form = new Form('添加字段');
        $type = Request::get('type');
        $form->addItem(
            (new Hidden('model_id', $model['id'])),
            (new Hidden('type', Request::get('type'))),
            (new Text('分组', 'group', '基本信息'))->setRequired()->setHelp('例如：基本信息'),
            (new Text('标题', 'title')),
            (new Text('字段名称', 'name'))->setHelp('字段名称只能由字母开头，字母、数字、下划线组成'),
            (new Text('表单类型', 'type', $type::getTitle()))->setDisabled(),
            (new Text('字段类型', 'fieldtype', $type::getFieldType()))->setRequired(),
        );
        $type::onCreateFieldForm($form);
        $form->addItem(
            (new Text('提示信息', 'help'))->setHelp('后台表单处的提示信息'),
            (new Radios('后台编辑', 'editable', 1))->addRadio(
                new Radio('不允许', 0),
                new Radio('允许', 1),
            ),
            (new Radios('后台列表显示', 'show', 0))->addRadio(
                new Radio('不显示', 0),
                new Radio('显示', 1),
            ),
            (new Textarea('后台显示模板', 'tpl'))->setHelp('自定义显示模板，额外变量：$field, $content'),
        );
        return $form;
    }

    public function post()
    {
        $model = Db::get('php94_cms_model', '*', [
            'id' => Request::post('model_id'),
        ]);

        $name = Request::post('name');
        if (!preg_match('/^[A-Za-z][A-Za-z0-9_]{0,78}[A-Za-z0-9]$/', $name)) {
            return Response::error("字段名称只能由字母开头，字母、数字、下划线组成");
        }

        if (Db::get('php94_cms_field', '*', [
            'model_id' => $model['id'],
            'name' => $name,
        ])) {
            return Response::error("字段名称不能重复");
        }

        $type = Request::post('type');
        $data = [
            'model_id' => $model['id'],
            'type' => $type,
            'name' => $name,
            'group' => Request::post('group', ''),
            'title' => Request::post('title', ''),
            'help' => Request::post('help', ''),
            'editable' => Request::post('editable', 1),
            'show' => Request::post('show', 0),
            'tpl' => Request::post('tpl', ''),
            'fieldtype' => Request::post('fieldtype', ''),
        ];
        $data['options'] = json_encode(array_diff_key(Request::post(), $data), JSON_UNESCAPED_UNICODE);

        Db::insert('php94_cms_field', $data);

        Db::query('ALTER TABLE <php94_cms_content_' . $model['name'] . '> ADD `' . $name . '` ' . Request::post('fieldtype'));

        return Response::success('操作成功！');
    }
}
