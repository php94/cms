<?php

declare(strict_types=1);

namespace App\Php94\Cms\Field;

use App\Php94\Cms\Interfaces\FieldInterface;
use PHP94\Facade\Router;
use PHP94\Form\Field\Picture;
use PHP94\Form\Form;
use PHP94\Help\Request;

class Pic implements FieldInterface
{
    public static function getTitle(): string
    {
        return '单图';
    }

    public static function onCreateFieldForm(Form $form)
    {
    }

    public static function getFieldType(): string
    {
        return 'varchar(255) NOT NULL DEFAULT \'\'';
    }

    public static function onUpdateFieldForm(Form $form, array $field)
    {
    }

    public static function onCreateContentForm(Form $form, array $field)
    {
        $form->addItem(
            (new Picture($field['title'], $field['name'], $field['default'] ?? ''))
                ->setUploadUrl(Router::build('/php94/admin/tool/upload'))
                ->setHelp($field['help'] ?? '')
        );
    }

    public static function onCreateContent(array &$content, array $field)
    {
        $content[$field['name']] = Request::post($field['name']);
    }

    public static function onUpdateContentForm(Form $form, array $field, array $content)
    {
        $form->addItem(
            (new Picture($field['title'], $field['name'], $content[$field['name']] ??''))
                ->setUploadUrl(Router::build('/php94/admin/tool/upload'))
                ->setHelp($field['help'] ?? '')
        );
    }

    public static function onUpdateContent(array &$content, array $field)
    {
        $content[$field['name']] = Request::post($field['name']);
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
        if (isset($content[$field['name']]) && strlen($content[$field['name']])) {
            return '<img src="' . $content[$field['name']] . '" alt="" width="60" height="60">';
        } else {
            return '';
        }
    }
}
