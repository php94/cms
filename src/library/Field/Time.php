<?php

declare(strict_types=1);

namespace App\Php94\Cms\Field;

use App\Php94\Cms\Interfaces\FieldInterface;
use PHP94\Form\Field\Text;
use PHP94\Help\Request;
use PHP94\Facade\Template;
use PHP94\Form\Field\Time as FieldTime;
use PHP94\Form\Form;

class Time implements FieldInterface
{
    public static function getTitle(): string
    {
        return '时间';
    }

    public static function onCreateFieldForm(Form $form)
    {
    }

    public static function getFieldType(): string
    {
        return 'time';
    }

    public static function onUpdateFieldForm(Form $form, array $field)
    {
    }

    public static function onCreateContentForm(Form $form, array $field)
    {
        $form->addItem(
            (new FieldTime($field['title'], $field['name'], $field['default'] ?? ''))->setHelp($field['help'] ?? '')
        );
    }

    public static function onCreateContent(array &$content, array $field)
    {
        $content[$field['name']] = Request::post($field['name']);
    }

    public static function onUpdateContentForm(Form $form, array $field, array $content)
    {
        $form->addItem(
            (new FieldTime($field['title'], $field['name'], $content[$field['name']] ?? ''))->setHelp($field['help'] ?? '')
        );
    }

    public static function onUpdateContent(array &$content, array $field)
    {
        $content[$field['name']] = Request::post($field['name']);
    }

    public static function getFilterForm(array $field): ?string
    {
        $tpl = <<<'str'
<div style="display: flex;flex-direction: column;gap: 5px;">
<div>
    <input type="time" class="form-control" name="filter[{$field['name']}][min]" value="{$request->get('filter.'.$field['name'].'.min')}">
</div>
<div>
    <input type="time" class="form-control" name="filter[{$field['name']}][max]" value="{$request->get('filter.'.$field['name'].'.max')}">
</div>
</div>
str;
        return Template::renderString($tpl, [
            'field' => $field
        ]);
    }

    public static function onFilter(array &$where, array $field)
    {
        $min = Request::get('filter.' . $field['name'] . '.min', '');
        $max = Request::get('filter.' . $field['name'] . '.max', '');
        if (is_string($min) && strlen($min) && is_string($max) && strlen($max)) {
            $where[$field['name'] . '[<>]'] = [$min, $max];
        } elseif (is_string($min) && strlen($min)) {
            $where[$field['name'] . '[>=]'] = $min;
        } elseif (is_string($max) && strlen($max)) {
            $where[$field['name'] . '[<=]'] = $max;
        }
    }

    public static function getShow($field, $content): string
    {
        return $content[$field['name']] ?? '';
    }
}
