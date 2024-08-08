<?php

declare(strict_types=1);

namespace App\Php94\Cms\Field;

use App\Php94\Cms\Interfaces\FieldInterface;
use PHP94\Form\Field\SelectLevel;
use PHP94\Form\Field\Textarea;
use PHP94\Form\Form;
use PHP94\Request;

class Select implements FieldInterface
{
    public static function getTitle(): string
    {
        return '单选';
    }

    public static function onCreateFieldForm(Form $form)
    {
        $form->addItem(
            (new Textarea('选项', 'items'))->setRequired()->setHelp('一行一个，格式：标题|值|父级值')
        );
    }

    public static function getFieldType(): string
    {
        return 'varchar(80) NOT NULL DEFAULT \'\'';
    }

    public static function onUpdateFieldForm(Form $form, array $field)
    {
        $form->addItem(
            (new Textarea('选项', 'items', $field['items']))->setRequired()->setHelp('一行一个，格式：标题|值|父级值')
        );
    }

    public static function onCreateContentForm(Form $form, array $field)
    {
        $select = (new SelectLevel($field['title'], $field['name'], ''))->setHelp($field['help'] ?? '');
        foreach (array_filter(explode(PHP_EOL, $field['items'])) as $vo) {
            $tmp = explode('|', trim($vo) . '||||');
            $select->addItem($tmp[0], $tmp[1], $tmp[2], $tmp[4], $tmp[3] ? true : false);
        }
        $form->addItem($select);
    }

    public static function onCreateContent(array &$content, array $field)
    {
        $content[$field['name']] = Request::post($field['name'], '');
    }

    public static function onUpdateContentForm(Form $form, array $field, array $content)
    {
        $select = (new SelectLevel($field['title'], $field['name'], $content[$field['name']] ?? ''))->setHelp($field['help'] ?? '');
        foreach (array_filter(explode(PHP_EOL, $field['items'])) as $vo) {
            $tmp = explode('|', trim($vo) . '||||');
            $select->addItem($tmp[0], $tmp[1], $tmp[2], $tmp[4], $tmp[3] ? true : false);
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
        foreach (array_filter(explode(PHP_EOL, $field['items'])) as $vo) {
            $tmp = explode('|', trim($vo) . '||||');
            $select->addItem($tmp[0], $tmp[1], $tmp[2], $tmp[4], $tmp[3] ? true : false);
        }
        return str_replace('<label class="form-label">' . $field['title'] . '</label>', '', $select . '');
    }

    public static function onFilter(array &$where, array $field)
    {
        $getsubval = function ($items, $val) use (&$getsubval): array {
            $res = [];
            array_push($res, addslashes($val));
            foreach ($items as $vo) {
                if ($vo['parent'] === $val) {
                    array_push($res, ...$getsubval($items, $vo['value']));
                }
            }
            return $res;
        };

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

        $vls = [];
        $tmp = Request::get('filter.' . $field['name'], '');
        if (!is_null($tmp) && strlen($tmp)) {
            $vls = $getsubval($items, $tmp);
        }
        if ($vls) {
            $where[$field['name']] = $vls;
        }
    }

    public static function getShow($field, $content): string
    {

        if (!isset($content[$field['name']])) {
            return '';
        }
        $value = $content[$field['name']];

        foreach (array_filter(explode(PHP_EOL, $field['items'])) as $vo) {
            $tmp = explode('|', trim($vo) . '|');
            if ($tmp[1] == $value) {
                return $tmp[0];
            }
        }

        return $value;
    }
}
