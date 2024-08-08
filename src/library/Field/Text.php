<?php

declare(strict_types=1);

namespace App\Php94\Cms\Field;

use App\Php94\Cms\Interfaces\FieldInterface;
use PHP94\Form\Field\Text as FieldText;
use PHP94\Form\Form;
use PHP94\Request;

class Text implements FieldInterface
{
    public static function getTitle(): string
    {
        return '单行文本';
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
            (new FieldText($field['title'], $field['name'], $field['default'] ?? ''))
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
            (new FieldText($field['title'], $field['name'], $content[$field['name']]?? ''))
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
        $q = trim(Request::get('q', ''));
        if (is_string($q) && strlen($q)) {
            $where['OR'][$field['name'] . '[~]'] = '%' . $q . '%';
        }
    }

    public static function getShow($field, $content): string
    {
        return $content[$field['name']] ?? '';
    }
}
