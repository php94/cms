<?php

declare(strict_types=1);

namespace App\Php94\Cms\Field;

use App\Php94\Cms\Interfaces\FieldInterface;
use PHP94\Template;
use PHP94\Form\Field\Text;
use PHP94\Request;
use PHP94\Form\Field\Number as FieldNumber;
use PHP94\Form\Form;

class Number implements FieldInterface
{
    public static function getTitle(): string
    {
        return '数字';
    }

    public static function getFieldType(): string
    {
        return 'float';
    }

    public static function onCreateFieldForm(Form $form)
    {
        $form->addItem(
            (new Text('最小值', 'min')),
            (new Text('最大值', 'max')),
            (new FieldNumber('步进', 'step')),
        );
    }

    public static function onUpdateFieldForm(Form $form, array $field)
    {
        $form->addItem(
            (new Text('最小值', 'min', $field['min'])),
            (new Text('最大值', 'max', $field['max'])),
            (new FieldNumber('步进', 'step', $field['step'])),
        );
    }

    public static function onCreateContentForm(Form $form, array $field)
    {
        $number = (new FieldNumber($field['title'], $field['name'], $field['default'] ?? '', 'number'))->setHelp($field['help'] ?? '');
        if (isset($field['min']) && is_numeric($field['min'])) {
            $number->setMin($field['min']);
        }
        if (isset($field['max']) && is_numeric($field['max'])) {
            $number->setMax($field['max']);
        }
        if (isset($field['step']) && is_numeric($field['step'])) {
            $number->setStep($field['step']);
        }
        $form->addItem($number);
    }

    public static function onCreateContent(array &$content, array $field)
    {
        $content[$field['name']] = Request::post($field['name']);
    }

    public static function onUpdateContentForm(Form $form, array $field, array $content)
    {
        $number = (new FieldNumber($field['title'], $field['name'], $content[$field['name']] ?? '', 'number'))->setHelp($field['help'] ?? '');
        if (isset($field['min']) && is_numeric($field['min'])) {
            $number->setMin($field['min']);
        }
        if (isset($field['max']) && is_numeric($field['max'])) {
            $number->setMax($field['max']);
        }
        if (isset($field['step']) && is_numeric($field['step'])) {
            $number->setStep($field['step']);
        }
        $form->addItem($number);
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
        <input type="number" class="form-control" name="filter[{$field['name']}][min]" value="{$request->get('filter.'.$field['name'].'.min')}">
    </div>
    <div>
        <input type="number" class="form-control" name="filter[{$field['name']}][max]" value="{$request->get('filter.'.$field['name'].'.max')}">
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
        return '' . $content[$field['name']];
    }
}
