<?php

declare(strict_types=1);

namespace App\Php94\Cms\Field;

use App\Php94\Cms\Interfaces\FieldInterface;
use PHP94\Help\Request;
use PHP94\Facade\Router;
use PHP94\Facade\Template;
use PHP94\Form\Field\Pictures;
use PHP94\Form\Form;

class Pics implements FieldInterface
{
    public static function getTitle(): string
    {
        return '多图';
    }

    public static function getFieldType(): string
    {
        return 'json';
    }

    public static function onCreateFieldForm(Form $form)
    {
    }

    public static function onUpdateFieldForm(Form $form, array $field)
    {
    }

    public static function onCreateContentForm(Form $form, array $field)
    {
        $pics = (new Pictures($field['title'], $field['name']))
            ->setUploadUrl(Router::build('/php94/admin/tool/upload'))
            ->setHelp($field['help'] ?? '');
        $form->addItem(
            $pics
        );
    }

    public static function onCreateContent(array &$content, array $field)
    {
        $content[$field['name']] = json_encode(
            Request::post($field['name'], []),
            JSON_UNESCAPED_UNICODE
        );
    }

    public static function onUpdateContentForm(Form $form, array $field, array $content)
    {
        $pics = (new Pictures($field['title'], $field['name']))
            ->setUploadUrl(Router::build('/php94/admin/tool/upload'))
            ->setHelp($field['help'] ?? '');
        if (!is_null($content[$field['name']])) {
            foreach (json_decode($content[$field['name']], true) as $vo) {
                $pics->addPic($vo['src'], $vo['size'], $vo['title']);
            }
        }
        $form->addItem(
            $pics
        );
    }

    public static function onUpdateContent(array &$content, array $field)
    {
        $content[$field['name']] = json_encode(
            Request::post($field['name'], []),
            JSON_UNESCAPED_UNICODE
        );
    }

    public static function getFilterForm(array $field): ?string
    {
        return '';
    }

    public static function onFilter(array &$where, array $field)
    {
    }

    public static function getShow($field, $content): string
    {
        $tpl = <<<'str'
<div style="display: flex;flex-direction: wrap;flex-wrap: wrap;gap: 5px;">
{foreach $items as $vo}
<div>
    <img src="{$vo.src}" alt="" width="60" height="60">
</div>
{/foreach}
</div>
str;
        return Template::renderString($tpl, [
            'field' => $field,
            'items' => is_null($content[$field['name']]) ? [] : json_decode($content[$field['name']], true),
        ]);
    }
}
