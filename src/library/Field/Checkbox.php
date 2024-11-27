<?php

declare(strict_types=1);

namespace App\Php94\Cms\Field;

use App\Php94\Cms\Interfaces\FieldInterface;
use PHP94\Request;
use PHP94\Form\Field\Checkbox as FieldCheckbox;
use PHP94\Form\Field\Checkboxs;
use PHP94\Form\Field\Radio;
use PHP94\Form\Field\Radios;
use PHP94\Form\Field\Textarea;
use PHP94\Form\Form;

class Checkbox implements FieldInterface
{
    public static function getTitle(): string
    {
        return '多选';
    }

    public static function getFieldType(): string
    {
        return 'json';
    }

    public static function onCreateFieldForm(Form $form)
    {
        $form->addItem((new Textarea('选项', 'items'))->setRequired()->setHelp('一行一个，格式：标题|值|父级值'));
        $form->addItem((new Radios('筛选类型', 'filtertype', 0))->addRadio(
            new Radio('单选', 0),
            new Radio('多选(或)', 1),
            new Radio('多选(且)', 2),
        ));
    }

    public static function onUpdateFieldForm(Form $form, array $field)
    {
        $form->addItem((new Textarea('选项', 'items', $field['items'] ?? ''))->setRequired()->setHelp('一行一个，格式：标题|值|父级值'));
        $form->addItem((new Radios('筛选类型', 'filtertype', $field['filtertype'] ?? 0))->addRadio(
            new Radio('单选', 0),
            new Radio('多选(或)', 1),
            new Radio('多选(且)', 2),
        ));
    }

    public static function onCreateContentForm(Form $form, array $field)
    {
        $form->addItem(
            (new Checkboxs($field['title'], $field['name'], []))->addCheckbox(...(function () use ($field): iterable {
                foreach (array_filter(explode(PHP_EOL, $field['items'])) as $vo) {
                    $tmp = explode('|', trim($vo) . '||||');
                    yield new FieldCheckbox($tmp[0], $tmp[1]);
                }
            })())->setHelp($field['help'] ?? '')
        );
    }

    public static function onCreateContent(array &$content, array $field)
    {
        $content[$field['name']] = json_encode(Request::post($field['name'], []), JSON_UNESCAPED_UNICODE);
    }

    public static function onUpdateContentForm(Form $form, array $field, array $content)
    {
        $form->addItem(
            (new Checkboxs($field['title'], $field['name'], is_null($content[$field['name']]) ? [] : json_decode($content[$field['name']], true)))->addCheckbox(...(function () use ($field): iterable {
                foreach (array_filter(explode(PHP_EOL, $field['items'])) as $vo) {
                    $tmp = explode('|', trim($vo) . '||||');
                    yield new FieldCheckbox($tmp[0], $tmp[1]);
                }
            })())->setHelp($field['help'] ?? '')
        );
    }

    public static function onUpdateContent(array &$content, array $field)
    {
        $content[$field['name']] = json_encode(Request::post($field['name'], []), JSON_UNESCAPED_UNICODE);
    }

    public static function getFilterTpl(): string
    {
        return <<<'str'
<?php
$items = [];
foreach (array_filter(explode(PHP_EOL, $field['items'])) as $vo) {
    $tmp = explode('|', trim($vo) . '||||');
    $items[] = [
        'title' => $tmp[0],
        'value' => $tmp[1],
        'parent' => $tmp[2],
        'disabled' => $tmp[3] ? true : false,
        'group' => $tmp[4],
    ];
}
?>
{switch $field['filtertype']}
{case '0'}
<div>
<input type="radio" style="display: none;" name="filter[{$field.name}]" value="{$request->get('filter.'.$field['name'])}" checked>
{foreach $items as $vo}
{if $vo['value'] === $request->get('filter.'.$field['name'])}
<label>
    <input type="radio" style="display: none;" name="filter[{$field.name}]" value="">
    <span class="btn btn-warning">{$vo.title}</span>
</label>
{else}
<label>
    <input type="radio" style="display: none;" name="filter[{$field.name}]" value="{$vo.value}">
    <span class="btn btn-light">{$vo.title}</span>
</label>
{/if}
{/foreach}
</div>
{/case}
{case '1'}
{case '2'}
<div>
{foreach $items as $vo}
{if in_array($vo['value'], (array)$request->get('filter.'.$field['name']))}
<label>
    <input type="checkbox" style="display: none;" name="filter[{$field.name}][]" value="{$vo.value}" autocomplete="off" checked>
    <span class="btn btn-warning">{$vo.title}</span>
</label>
{else}
<label>
    <input type="checkbox" style="display: none;" name="filter[{$field.name}][]" value="{$vo.value}" autocomplete="off">
    <span class="btn btn-light">{$vo.title}</span>
</label>
{/if}
{/foreach}
</div>
{/case}
{default}
{/default}
{/switch}
str;
    }

    public static function onFilter(array &$where, array $field)
    {
        $value = Request::get('filter.' . $field['name']);
        switch ($field['filtertype']) {
            case '0':
                if (is_string($value) && strlen($value)) {
                    $where[$field['name'] . '[~]'] = '"' . $value . '"';
                }
                break;

            case '1':
                if ($value && is_array($value)) {
                    $where[$field['name'] . '[~]'] = [
                        'OR' => $value,
                    ];
                }
                break;

            case '2':
                if ($value && is_array($value)) {
                    $where[$field['name'] . '[~]'] = [
                        'AND' => $value,
                    ];
                }
                break;

            default:
                break;
        }
    }

    public static function getShowTpl(): string
    {
        return <<<'str'
<?php
if (!isset($content[$field['name']])) {
    echo '';
}else{
    $values = is_null($content[$field['name']]) ? [] : json_decode($content[$field['name']], true);
    $selected = [];
    foreach (array_filter(explode("\r\n", $field['items'])) as $vo) {
        $tmp = explode('|', trim($vo) . '|');
        if (in_array($tmp[1], $values)) {
            $selected[] = $tmp[0];
        }
    }
    echo implode(',', $selected);
}
?>
str;
    }
}
