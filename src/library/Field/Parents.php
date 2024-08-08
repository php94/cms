<?php

declare(strict_types=1);

namespace App\Php94\Cms\Field;

use App\Php94\Cms\Interfaces\FieldInterface;
use PHP94\Db;
use PHP94\Form\Field\SelectLevel;
use PHP94\Form\Form;
use PHP94\Request;

class Parents implements FieldInterface
{
    public static function getTitle(): string
    {
        return '父级';
    }

    public static function onCreateFieldForm(Form $form)
    {
    }

    public static function getFieldType(): string
    {
        return 'int(10) unsigned NOT NULL DEFAULT 0 COMMENT \'父级\'';
    }

    public static function onUpdateFieldForm(Form $form, array $field)
    {
    }

    public static function onCreateContentForm(Form $form, array $field)
    {
        $select = (new SelectLevel($field['title'], $field['name']))->setHelp($field['help'] ?? '');
        $model = Db::get('php94_cms_model', '*', [
            'id' => $field['model_id'],
        ]);
        foreach (Db::select('php94_cms_content_' . $model['name'], '*') as $vo) {
            $select->addItem((string)($vo['title'] ?? $vo['id']), $vo['id'], $vo[$field['name']] ?: '');
        }
        $form->addItem($select);
    }

    public static function onCreateContent(array &$content, array $field)
    {
        $content[$field['name']] = Request::post($field['name'], 0);
    }

    public static function onUpdateContentForm(Form $form, array $field, array $content)
    {
        $select = (new SelectLevel($field['title'], $field['name'], $content[$field['name']] ?: ''))->setHelp($field['help'] ?? '');
        $model = Db::get('php94_cms_model', '*', [
            'id' => $field['model_id'],
        ]);
        foreach (Db::select('php94_cms_content_' . $model['name'], '*') as $vo) {
            $select->addItem((string)($vo['title'] ?? $vo['id']), $vo['id'], $vo[$field['name']] ?: '');
        }
        $form->addItem($select);
    }

    public static function onUpdateContent(array &$content, array $field)
    {
        $content[$field['name']] = Request::post($field['name'], 0);
    }

    public static function getFilterForm(array $field): string
    {
        $select = new SelectLevel($field['title'], 'filter[' . $field['name'] . ']', Request::get('filter.' . $field['name']));
        $model = Db::get('php94_cms_model', '*', [
            'id' => $field['model_id'],
        ]);
        foreach (Db::select('php94_cms_content_' . $model['name'], '*') as $vo) {
            $select->addItem((string)($vo['title'] ?? $vo['id']), $vo['id'], $vo[$field['name']] ?: '');
        }
        return str_replace('<label class="form-label">' . $field['title'] . '</label>', '', $select . '');
    }

    public static function onFilter(array &$where, array $field)
    {
        $where[$field['name']] = Request::get('filter.' . $field['name'], '');
    }

    public static function getShow($field, $content): string|int
    {
        if (!isset($content[$field['name']])) {
            return '';
        }
        if (!$model = Db::get('php94_cms_model', '*', [
            'id' => $field['model_id'],
        ])) {
            return $content[$field['name']] ?: '';
        }
        if (!$data = Db::get('php94_cms_content_' . $model['name'], '*', [
            'id' => $content[$field['name']],
        ])) {
            return $content[$field['name']] ?: '';
        } else {
            return $data['title'] ?? $content[$field['name']];
        }
    }
}
