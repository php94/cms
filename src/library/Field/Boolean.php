<?php

declare(strict_types=1);

namespace App\Php94\Cms\Field;

use App\Php94\Cms\Interfaces\FieldInterface;
use PHP94\Request;
use PHP94\Form\Field\Radio;
use PHP94\Form\Field\Radios;
use PHP94\Form\Form;

class Boolean implements FieldInterface
{
    public static function getTitle(): string
    {
        return '布尔';
    }

    public static function getFieldType(): string
    {
        return 'tinyint(3) unsigned';
    }

    public static function onCreateFieldForm(Form $form) {}

    public static function onUpdateFieldForm(Form $form, array $field) {}

    public static function onCreateContentForm(Form $form, array $field)
    {
        $form->addItem(
            (new Radios($field['title'], $field['name'], 0))
                ->addRadio(
                    new Radio('否', 0),
                    new Radio('是', 1),
                )->setHelp($field['help'] ?? '')
        );
    }

    public static function onCreateContent(array &$content, array $field)
    {
        $content[$field['name']] = Request::post($field['name']) ? true : false;
    }

    public static function onUpdateContentForm(Form $form, array $field, array $content)
    {
        $form->addItem(
            (new Radios($field['title'], $field['name'], $content[$field['name']] ?? 0))
                ->addRadio(
                    new Radio('否', 0),
                    new Radio('是', 1),
                )->setHelp($field['help'] ?? '')
        );
    }

    public static function onUpdateContent(array &$content, array $field)
    {
        $content[$field['name']] = Request::post($field['name']) ? true : false;
    }

    public static function getFilterTpl(): string
    {
        return <<<'str'
<input type="radio" style="display: none;" name="filter[{$field.name}]" value="{$request->get('filter.'.$field['name'], '')}" checked>
{if $request->get('filter.'.$field['name'], '') == 1}
<label>
<input type="radio" style="display: none;" name="filter[{$field.name}]" value="">
<span class="btn btn-warning">是</span>
</label>
{else}
<label>
<input type="radio" style="display: none;" name="filter[{$field.name}]" value="1">
<span class="btn btn-light">是</span>
</label>
{/if}
{if $request->get('filter.'.$field['name'], '') == 0}
<label>
<input type="radio" style="display: none;" name="filter[{$field.name}]" value="">
<span class="btn btn-warning">否</span>
</label>
{else}
<label>
<input type="radio" style="display: none;" name="filter[{$field.name}]" value="0">
<span class="btn btn-light">否</span>
</label>
{/if}
str;
    }

    public static function onFilter(array &$where, array $field)
    {
        $v = Request::get('filter.' . $field['name']);
        if (is_string($v) && strlen($v)) {
            if ($v) {
                $where[$field['name']] = 1;
            } else {
                $where[$field['name']] = 0;
            }
        }
    }

    public static function getShowTpl(): string
    {
        return <<<'str'
{if $content[$field['name']]}是{else}否{/if}
str;
    }
}
