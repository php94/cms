<?php

declare(strict_types=1);

namespace App\Php94\Cms\Http\Model;

use App\Php94\Admin\Http\Common;
use PHP94\Db;
use PHP94\Template;

class Index extends Common
{
    public function get()
    {
        $models = Db::select('php94_cms_model', '*');
        return Template::render('model/index@php94/cms', [
            'models' => $models,
        ]);
    }
}
