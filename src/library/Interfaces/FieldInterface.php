<?php

declare(strict_types=1);

namespace App\Php94\Cms\Interfaces;

use PHP94\Form\Form;

interface FieldInterface
{
    public static function getTitle(): string;
    public static function getFieldType(): string;
    public static function getShowTpl(): string;

    public static function onCreateFieldForm(Form $form);
    public static function onUpdateFieldForm(Form $form, array $field);

    public static function onCreateContentForm(Form $form, array $field);
    public static function onCreateContent(array &$content, array $field);
    public static function onUpdateContentForm(Form $form, array $field, array $content);
    public static function onUpdateContent(array &$content, array $field);

    public static function getFilterTpl(): string;
    public static function onFilter(array &$where, array $field);
}
