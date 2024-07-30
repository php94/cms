<?php

declare(strict_types=1);

namespace App\Php94\Cms\Http\Field;

use App\Php94\Admin\Http\Common;
use PHP94\Help\Response;
use PHP94\Facade\Db;
use PHP94\Form\Form;
use PHP94\Form\Field\Text;
use PHP94\Form\Field\Hidden;
use PHP94\Form\Field\Radio;
use PHP94\Form\Field\Radios;
use PHP94\Form\Field\Textarea;
use PHP94\Help\Request;

class Update extends Common
{
    public function get()
    {
        $field = Db::get('php94_cms_field', '*', [
            'id' => Request::get('id'),
        ]);
        $field = array_merge(json_decode($field['options'], true), $field);
        $model = Db::get('php94_cms_model', '*', [
            'id' => $field['model_id'],
        ]);
        $form = new Form('编辑字段');
        $form->addItem(
            (new Hidden('id', $field['id'])),
            (new Text('分组', 'group', $field['group']))->setRequired()->setHelp('例如：基本信息'),
            (new Text('标题', 'title', $field['title']))->setHelp('例如：客户电话'),
            (new Text('字段名称', 'name', $field['name']))->setDisabled(),
            (new Text('表单类型', 'type', $field['type']::getTitle()))->setDisabled(),
        );
        $field['type']::onUpdateFieldForm($form, $field);
        $form->addItem(
            (new Text('提示信息', 'help', $field['help']))->setHelp('后台表单处的提示信息'),
            (new Radios('后台编辑', 'editable', $field['editable']))->addRadio(
                new Radio('不允许', 0),
                new Radio('允许', 1),
            ),
            (new Radios('后台列表显示', 'show', $field['show']))->addRadio(
                new Radio('不显示', 0),
                new Radio('显示', 1),
            ),
            (new Textarea('后台显示模板', 'tpl', $field['tpl']))->setHelp('自定义显示模板，额外变量：$field, $content'),
        );
        return $form;
    }

    public function post()
    {
        $field = Db::get('php94_cms_field', '*', [
            'id' => Request::post('id'),
        ]);

        $update = [
            'group' => Request::post('group'),
            'title' => Request::post('title'),
            'help' => Request::post('help'),
            'editable' => Request::post('editable', 1),
            'show' => Request::post('show', 0),
            'tpl' => Request::post('tpl', ''),
        ];

        $diff = array_diff_key(Request::post(), $update, ['id' => '']);
        $update['options'] = json_encode(array_merge(json_decode($field['options'], true), $diff), JSON_UNESCAPED_UNICODE);

        Db::update('php94_cms_field', $update, [
            'id' => $field['id'],
        ]);

        return Response::success('操作成功！');
    }
}
