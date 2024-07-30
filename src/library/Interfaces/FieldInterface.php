<?php

declare(strict_types=1);

namespace App\Php94\Cms\Interfaces;

use PHP94\Form\Form;

interface FieldInterface
{
    public static function getTitle(): string;
    public static function getFieldType(): string;

    public static function onCreateFieldForm(Form $form);
    public static function onUpdateFieldForm(Form $form, array $field);

    public static function onCreateContentForm(Form $form, array $field);
    public static function onCreateContent(array &$content, array $field);
    public static function onUpdateContentForm(Form $form, array $field, array $content);
    public static function onUpdateContent(array &$content, array $field);

    public static function getFilterForm(array $field): ?string;
    public static function onFilter(array &$where, array $field);

    /**
     * 获取默认后台显示模板，支持的变量$field, $content等
     */
    public static function getShow($field, $content): int|float|string|null;
}
