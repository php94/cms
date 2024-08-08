<?php

declare(strict_types=1);

namespace App\Php94\Cms\Http\Field;

use App\Php94\Admin\Http\Common;
use App\Php94\Cms\Model\FieldProvider;
use PHP94\Db;
use PHP94\Template;
use PHP94\Request;

class Index extends Common
{
    public function get(
        FieldProvider $fieldProvider
    ) {
        $model = Db::get('php94_cms_model', '*', [
            'id' => Request::get('model_id'),
        ]);
        $fields = Db::select('php94_cms_field', '*', [
            'model_id' => $model['id'],
            'ORDER' => [
                'priority' => 'DESC',
                'id' => 'ASC',
            ],
        ]);
        return Template::render('field/index@php94/cms', [
            'model' => $model,
            'fields' => $fields,
            'fieldtypes' => $fieldProvider->all(),
        ]);
    }
}
